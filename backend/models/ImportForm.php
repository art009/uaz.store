<?php

namespace backend\models;

use common\components\AppHelper;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class ImportForm
 *
 * @property UploadedFile $file
 *
 * @package backend\models
 */
class ImportForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var array
     */
    public $counts = [
        'insert' => 0,
        'update' => 0,
        'delete' => 0,
    ];

    /**
     * Маппинг колонок на атрибуты товара
     *
     * @var array
     */
    static $columnMap = [
        0 => 'external_id', //A
        1 => 'title',       //B
        2 => 'shop_title',
        3 => 'provider_title',
        4 => 'shop_code',
        5 => 'provider_code',
        6 => 'price',
        7 => 'unit',
        8 => 'manufacturer',
        9 => 'link',
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
     * Импорт
     *
     * @return bool
     */
    public function import()
    {
        if ($this->validate()) {
            $data = $this->getFileData();
            if ($data) {
                ini_set('memory_limit', '256M');
                $productsData = CatalogProduct::find()->asArray()->all();
                $links = $productExternalIds = [];
                foreach ($productsData as $productData) {
                    $links[] = md5($productData['link']);
                    $productExternalIds[$productData['id']] = $productData['external_id'];
                }

                $updateItems = $hideItems = [];
                $items = [];
                foreach ($data as $row) {
                    $item = [];
                    foreach (self::$columnMap as $key => $attribute) {
                        $item[$attribute] = array_key_exists($key, $row) ? $row[$key] : null;
                    }
                    if ($item['external_id'] && $item['title']) {
                        $item['unit'] = trim($item['unit'], '.');
                        $item['link'] = AppHelper::transliteration($item['title']);
                        $k = 1;
                        $link = md5($item['link']);
                        while (in_array($link, $links)) {
                            $k++;
                            $item['link'] .= '-' . $k;
                            $link = md5($item['link']);
                        }
                        $links[] = $link;
                        $items[$item['external_id']] = $item;
                    }
                }
                unset($data);
                foreach ($productExternalIds as $productId => $productExternalId) {
                    if (array_key_exists($productExternalId, $items)) {
                        $updateItems[$productId] = $items[$productExternalId]['price'];
                        unset($items[$productExternalId]);
                    } else {
                        $hideItems[] = $productId;
                    }
                }

                // Добавление товаров
                if ($items) {
                    $part = array_splice($items, 0, 100);
                    while (!empty($part)) {
                        $this->counts['insert'] += Yii::$app->db->createCommand()
                            ->batchInsert(CatalogProduct::tableName(), self::$columnMap, $part)
                            ->execute();

                        $part = array_splice($items, 0, 100);
                    }
                }
                // Обновление товаров
                if ($updateItems) {
                    foreach ($updateItems as $productId => $productPrice) {
                        $this->counts['update'] += Yii::$app->db->createCommand()
                            ->update(CatalogProduct::tableName(), ['price' => $productPrice], ['id' => $productId])
                            ->execute();
                    }
                }

                // Скрытие товаров
                if ($hideItems) {
                    $part = array_splice($hideItems, 0, 100);
                    while (!empty($part)) {
                        $this->counts['delete'] += CatalogProduct::updateAll(['hide' => AppHelper::YES], ['id' => $part]);
                        $part = array_splice($hideItems, 0, 100);
                    }
                }
            } else {
                $this->addError('file', 'Данные не получены из файла.');
            }
        }

        return true;
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
                $data = $document->getActiveSheet()->toArray(null, false, false, false);
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
}
