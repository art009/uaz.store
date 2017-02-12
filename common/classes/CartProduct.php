<?php

namespace common\classes;

use common\models\CatalogProduct;

/**
 * Class CartProduct
 *
 * @package common\classes
 *
 * @property CatalogProduct $product
 * @property integer $quantity
 */
class CartProduct
{
	/**
	 * @var CatalogProduct
	 */
	protected $product;

	/**
	 * @var integer
	 */
	protected $quantity;

	/**
	 * CartProduct constructor.
	 *
	 * @param CatalogProduct $product
	 * @param int $quantity
	 */
	public function __construct(CatalogProduct $product, $quantity = 0)
	{
		$this->product = $product;
		$this->quantity = $quantity;
	}

	/**
	 * @return CatalogProduct
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * @return int
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}
}
