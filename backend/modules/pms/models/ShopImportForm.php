<?php

namespace backend\modules\pms\models;

use app\modules\pms\models\ShopItem;
use common\components\AppHelper;
use common\models\ImportForm;
use yii\helpers\ArrayHelper;

/**
 * Class ShopImportForm
 *
 * @package backend\modules\pms\models
 */
class ShopImportForm extends ImportForm
{
	/**
	 * @var string
	 */
	public $vendor_code = 'A';

	/**
	 * @var string
	 */
	public $code = 'B';

	/**
	 * @var string
	 */
	public $title = 'C';

	/**
	 * @var string
	 */
	public $price = 'D';

	/**
	 * @var string
	 */
	public $unit = 'E';

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return parent::rules() + [
			[['code', 'title', 'unit', 'vendor_code', 'price'], 'required'],
			[['code', 'title', 'unit', 'vendor_code', 'price'], 'string', 'max' => 255],
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
		} else {
			$existed = ArrayHelper::map(ShopItem::find()->select(['code', 'price'])->asArray()->all(), 'code', 'price');
			$insertItems = $updateItems = $deleteItems = [];
			$names = $this->getAttributeNames();
			$date = date('Y-m-d H:i:s');
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
				if ($item['code']) {
					if (array_key_exists($item['code'], $existed)) {
						if ((float)$item['price'] != $existed[$item['code']]) {
							$updateItems[$item['code']] = (float)$item['price'];
						}
						unset($existed[$item['code']]);
					} else {
						$item['percent'] = 25;
						$item['created_at'] = $date;
						$insertItems[$item['code']] = $item;
					}
				}
			}
			$db = \Yii::$app->getDb();
			// Добавление товаров
			if ($insertItems) {
				$names[] = 'percent';
				$names[] = 'created_at';
				$part = array_splice($insertItems, 0, 100);
				while (!empty($part)) {
					$inserted = $db->createCommand()
						->batchInsert(ShopItem::tableName(), $names, $part)
						->execute();

					$this->addCounterValue(self::COUNTER_INSERT, $inserted);
					$part = array_splice($insertItems, 0, 100);
				}
			}
			// Обновление товаров
			if ($updateItems) {
				foreach ($updateItems as $code => $price) {
					$updated = $db->createCommand()
						->update(ShopItem::tableName(), ['price' => $price, 'updated_at' => $date], [
							'code' => $code,
							'ignored' => AppHelper::NO,
						])
						->execute();

					$this->addCounterValue(self::COUNTER_UPDATE, $updated);
				}
			}

			// Скрытие товаров
			if ($existed) {
				$part = array_splice($existed, 0, 50);
				while (!empty($part)) {
					$deleted = ShopItem::updateAll(['ignored' => AppHelper::YES], ['code' => $part]);

					$this->addCounterValue(self::COUNTER_DELETE, $deleted);
					$part = array_splice($existed, 0, 50);
				}
			}
		}
	}
}
