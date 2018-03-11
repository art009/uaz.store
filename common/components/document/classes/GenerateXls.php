<?php

namespace common\components\document\classes;


use PHPExcel;
use PHPExcel_DocumentProperties;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel5;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

class GenerateXls extends BaseObject
{
    const SCREEN = '%';

    /**
     * @var string
     */
    protected $templateFile;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $resultFile;

    /**
     * @var PHPExcel
     */
    private $objPHPExcel;

    /**
     * GenerateXls constructor.
     * @param string $templateFile
     * @param string $resultFile
     * @param array $data
     * @param array $config
     */
    public function __construct(string $templateFile, string $resultFile, array $data, array $config = [])
    {
        $this->templateFile = $templateFile;
        $this->resultFile = $resultFile;
        $this->data = $data;

        $this->validation();

        parent::__construct($config);
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     */
    protected function validationTemplateFile()
    {
        if (file_exists($this->templateFile)) {
            return true;
        }

        throw new InvalidConfigException("Template file not found.");
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     */
    protected function validationData()
    {
        if (is_array($this->data) and !empty($this->data)) {
            return true;
        }

        throw new InvalidConfigException("Empty data.");
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     */
    protected function validationResultFile()
    {
        $dirname = dirname($this->resultFile);
        if ($dirname == '.') {
            throw new InvalidConfigException("Is not the right path for the output file.");
        }
        FileHelper::createDirectory($dirname, 0777, true);

        $basename = basename($this->resultFile);
        if (strrpos($basename, '.xls') === false) {
            throw new InvalidConfigException("Invalid file extension. The extension must be a xls.");
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function validation()
    {
        return $this->validationTemplateFile() and
            $this->validationData() and
            $this->validationResultFile();
    }

    /**
     * Initialize the object
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->createObjPHPExcel();
    }

    /**
     * Create PHPExcel
     * @return void
     */
    protected function createObjPHPExcel()
    {
        $this->objPHPExcel = PHPExcel_IOFactory::load($this->templateFile);
        $this->objPHPExcel->setActiveSheetIndex(0);
    }

    /**
     * @param string $prefix
     * @param string $posfix
     * @return void
     */
    public function setScreening($prefix = self::SCREEN, $posfix = self::SCREEN)
    {
        GenerateXlsByStringData::setScreening($prefix, $posfix);
        GenerateXlsByArrayData::setScreening($prefix, $posfix);
    }

    /**
     * @return PHPExcel_DocumentProperties
     */
    public function getObjPHPExcelProperties()
    {
        return $this->objPHPExcel->getProperties();
    }

    /**
     * Execute create file
     * @return bool|string Return result file or false
     */
    public function execute()
    {
        $aSheet = $this->objPHPExcel->getActiveSheet();
        GenerateXlsByArrayData::generate($aSheet, $this->data);
        GenerateXlsByStringData::generate($aSheet, $this->data);

        $this->saveFile();

        $this->objPHPExcel->disconnectWorksheets();
        unset($this->objPHPExcel);

        if (file_exists($this->resultFile))
            return $this->resultFile;

        return false;
    }

    /**
     * Save new xls file
     * @return void
     */
    protected function saveFile()
    {
        $objWriter = new PHPExcel_Writer_Excel5($this->objPHPExcel);
        $objWriter->save($this->resultFile);
    }
}