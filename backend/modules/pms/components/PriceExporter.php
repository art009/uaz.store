<?php

namespace backend\modules\pms\components;

use yii\db\Connection;

/**
 * Class PriceExporter
 *
 * @package backend\modules\pms\components
 */
class PriceExporter
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
	 * @param bool|false $recalculate
	 *
	 * @return int
	 */
	public function export(bool $recalculate = false): int
	{
		if ($recalculate) {
			$this->calculate();
		}

		return $this->db->createCommand("
			UPDATE catalog_product 
			SET price = (
				SELECT site_price FROM shop_item WHERE shop_item.code = catalog_product.external_id
			)
		")->execute();
	}

	/**
	 * @return int
	 */
	public function calculate()
	{
		return $this->db->createCommand("
			UPDATE shop_item SET site_price = (
				SELECT ROUND(SUM(provider_item.price), 2) 
				FROM provider_item_to_shop_item
				LEFT JOIN provider_item ON provider_item_to_shop_item.provider_item_id = provider_item.id
				WHERE provider_item_to_shop_item.shop_item_id = shop_item.id
			) * (1 + percent / 100)
		")->execute();
	}
}
