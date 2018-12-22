<?php

namespace backend\modules\pms\components;

use app\modules\pms\models\ShopItem;
use common\components\AppHelper;
use common\models\CatalogProduct;
use yii\db\Connection;

/**
 * Class ProductExporter
 *
 * @package backend\modules\pms\components
 */
class ProductExporter
{
	/**
	 * @var Connection
	 */
	protected $db;

	/**
	 * PriceExporter constructor.
	 *
	 * @param Connection $db
	 */
	public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	/**
	 * @return int
	 *
	 * @throws \yii\db\Exception
	 */
	public function export(): int
	{
		$result = 0;

		ini_set('memory_limit', '256M');

		$this->fillTransliterationList();
		$data = $this->getShopItemsData();
		if ($data) {
			$items = [];
			foreach ($data as $row) {
				$items[] = [
					'title' => $row['title'],
					'link' => AppHelper::transliteration($row['title']),
					'price' => $row['site_price'] ? $row['site_price'] : $row['price'],
					'shop_code' => $row['vendor_code'],
					'external_id' => $row['code'],
					'unit' => trim($row['unit'], " \t\n\r\0\x0B."),
				];
			}
			if ($items) {
				$part = array_splice($items, 0, 100);
				while (!empty($part)) {
					$result += $this->db->createCommand()
						->batchInsert(CatalogProduct::tableName(), [
							'title',
							'link',
							'price',
							'shop_code',
							'external_id',
							'unit',
						], $part)
						->execute();

					$part = array_splice($items, 0, 100);
				}
			}
		}

		return $result;
	}

	/**
	 * Заполнения статического списка транслита ссылок
	 */
	protected function fillTransliterationList()
	{
		$productsLinks = CatalogProduct::find()->select(['link'])->asArray()->column();
		foreach ($productsLinks as $link) {
			AppHelper::addTransliterationLink($link);
		}
	}

	/**
	 * @return array
	 */
	protected function getShopItemsData(): array
	{
		return ShopItem::find()
			->select(['code', 'vendor_code', 'title', 'price', 'site_price', 'unit'])
			->andWhere("NOT EXISTS (
				SELECT id 
				FROM catalog_product 
				WHERE shop_item.code = catalog_product.external_id
			)")
			->asArray()
			->all();
	}
}
