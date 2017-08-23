<?php

namespace common\models;


use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class ImportForm
 *
 * @property UploadedFile $file
 *
 * @package common\models
 */
abstract class ImportForm extends Model
{
	const COUNTER_INSERT = 'insert';
	const COUNTER_UPDATE = 'update';
	const COUNTER_DELETE = 'delete';

    /**
     * @var UploadedFile
     */
    public $file;

	/**
	 * @var array
	 */
    protected $counters = [
    	self::COUNTER_INSERT => 0,
	    self::COUNTER_UPDATE => 0,
	    self::COUNTER_DELETE => 0,
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx,csv', 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Файл',
        ];
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName = null);
        $this->file = UploadedFile::getInstance($this, 'file');

        return $result;
    }

	/**
	 * Обработка данных
	 *
	 * @param array $data
	 *
	 * @return void
	 */
    abstract protected function process(array $data);

    /**
     * Импорт
     *
     * @return bool
     */
    public function import()
    {
        if ($this->validate()) {
            $data = $this->getFileData();
            $this->process($data);
        }

        return !$this->hasErrors();
    }

    /**
     * Получение данных из загруженного файла
     *
     * @return array
     */
    protected function getFileData()
    {
        $data = [];
        if ($this->file) {
            try {
                $document = \PHPExcel_IOFactory::load($this->file->tempName);
                $data = $document->getActiveSheet()->toArray(null, false, false, true);
                $document->disconnectWorksheets();
                unset($document);
            } catch (\Exception $e) {
                $this->addError('file', 'Ошибка при получении данных из файла: ' . $e->getMessage());
            }
        } else {
            $this->addError('file', 'Не удалось загрузить файл.');
        }

        return $data;
    }

	/**
	 * @param string $counterName
	 * @param int $value
	 */
    protected function setCounterValue(string $counterName, int $value)
    {
    	$this->counters[$counterName] = $value;
    }

	/**
	 * @param string $counterName
	 *
	 * @return int
	 */
    public function getCounterValue(string $counterName)
    {
    	return $this->counters[$counterName] ?? 0;
    }

	/**
	 * @param string $counterName
	 * @param int $value
	 */
    protected function addCounterValue(string $counterName, int $value)
    {
    	$this->counters[$counterName] += $value;
    }
}
