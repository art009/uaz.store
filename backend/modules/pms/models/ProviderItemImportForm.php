<?php

namespace backend\modules\pms\models;

use app\modules\pms\models\ProviderItem;
use backend\modules\pms\components\ProviderItemAcceptCache;
use common\components\AppHelper;
use common\models\ImportForm;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ProviderItemImportForm
 *
 * @package backend\modules\pms\models
 */
class ProviderItemImportForm extends ImportForm
{
    /**
     * @var int
     */
    public $provider_id;

    /**
     * @var string
     */
    public $title = 'A';

    /**
     * @var string
     */
    public $rest = 'B';

    /**
     * @var string
     */
    public $unit = 'C';

    /**
     * @var string
     */
    public $code = 'D';

    /**
     * @var string
     */
    public $vendor_code = 'E';

    /**
     * @var string
     */
    public $manufacturer = 'F';

    /**
     * @var string
     */
    public $price = 'G';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules() + [
                [['code', 'title', 'unit', 'vendor_code', 'price', 'rest', 'manufacturer', 'provider_id'], 'required'],
                [['code', 'title', 'unit', 'vendor_code', 'price', 'rest', 'manufacturer'], 'string', 'max' => 255],
                [['provider_id'], 'integer']
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return parent::attributeLabels() + [
                'code' => 'Столбец с кодом',
                'title' => 'Столбец с названием',
                'price' => 'Столбец с ценой',
                'vendor_code' => 'Столбец с артикулом',
                'unit' => 'Столбец с единицей измерения',
                'rest' => 'Столбец с остатком',
                'manufacturer' => 'Столбец с производителем',
            ];
    }

    /**
     * @return array
     */
    public function getAttributeNames()
    {
        $result = [];

        foreach ($this->attributeLabels() as $attribute => $label) {
            if (in_array($attribute, array_keys(parent::attributeLabels()))) {
                continue;
            }
            $result[] = $attribute;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function process(array $data)
    {
        if (empty($data)) {
            $this->addError('file', 'Данные не получены из файла.');
            return false;
        }
        $storedData = ProviderItem::find()
            ->select(['code', 'price', 'ignored'])
            ->where(['provider_id' => $this->provider_id])
            ->asArray()
            ->all();

        $freshUpload = empty($storedData);
        $existed = ArrayHelper::map($storedData, 'code', 'price');
        $ignored = ArrayHelper::map($storedData, 'code', 'ignored');
        $insertItems = $updateItems = $acceptItems = $deleteItems = [];
        $names = $this->getAttributeNames();
        $date = date('Y-m-d H:i:s');
        $processedCodes = $codesForAlert = [];
        foreach ($data as $row) {
            $item = [];
            foreach ($names as $attribute) {
                $key = $this->{$attribute};
                if (array_key_exists($key, $row)) {
                    $item[$attribute] = trim($row[$key]);
                } else {
                    $this->addError($attribute, 'Не найден столбец: ' . $key);
                    break(2);
                }
            }
            if (!$item['code']) {
                continue;
            }
            if (in_array($item['code'], $processedCodes)) {
                $codesForAlert[] = $item['code'];
            } else {
                $processedCodes[] = $item['code'];
            }
            $item['price'] = round($item['price'], 2);
            if (array_key_exists($item['code'], $existed)) {
                if ($item['price'] != $existed[$item['code']] && !$ignored[$item['code']]) {
                    if ($existed[$item['code']] < $item['price']) {
                        $item['old_price'] = $existed[$item['code']];
                        $acceptItems[$item['code']] = $item;
                    } else {
                        $updateItems[$item['code']] = $item['price'];
                    }
                }
                unset($existed[$item['code']]);
            } else {
                if ($freshUpload) {
                    $acceptItems[$item['code']] = $item;
                }
                $item['created_at'] = $date;
                $item['provider_id'] = $this->provider_id;
                $insertItems[$item['code']] = $item;
            }

        }
        if (count($codesForAlert) > 0) {
            Yii::$app->session->setFlash('error',
                'В файле найдены дубликаты кодов. Измените строки с ними или удалите лишнее. Коды-дубли: ' . implode(", ",
                    $codesForAlert));
            $this->addError('file', 'Некорректный прайс лист');
            return false;
        }

        $db = \Yii::$app->getDb();
        // Добавление товаров
        if ($insertItems) {
            $names[] = 'created_at';
            $names[] = 'provider_id';
            $part = array_splice($insertItems, 0, 100);
            while (!empty($part)) {
                $inserted = $db->createCommand()
                    ->batchInsert(ProviderItem::tableName(), $names, $part)
                    ->execute();

                $this->addCounterValue(self::COUNTER_INSERT, $inserted);
                $part = array_splice($insertItems, 0, 100);
            }
        }

        // Обновление товаров
        if ($updateItems) {
            foreach ($updateItems as $code => $price) {
                $updated = $db->createCommand()
                    ->update(ProviderItem::tableName(), ['price' => $price, 'updated_at' => $date], [
                        'code' => $code,
                        'ignored' => AppHelper::NO,
                        'provider_id' => $this->provider_id
                    ])
                    ->execute();

                $this->addCounterValue(self::COUNTER_UPDATE, $updated);
            }
        }

        // Подтверждение обновления товаров
        if ($acceptItems) {
            $cache = new ProviderItemAcceptCache($this->provider_id);
            $cache->set($acceptItems);
        }

        // Скрытие товаров
        if ($existed) {
            $part = array_splice($existed, 0, 50);
            while (!empty($part)) {
                $deleted = ProviderItem::updateAll(['ignored' => AppHelper::YES],
                    ['code' => $part, 'provider_id' => $this->provider_id]);

                $this->addCounterValue(self::COUNTER_DELETE, $deleted);
                $part = array_splice($existed, 0, 50);
            }
        }
    }
}
