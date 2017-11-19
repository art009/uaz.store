<?php

namespace frontend\widgets;

use common\models\CatalogProduct;
use yii\base\Widget;

/**
 * Class ProductItem
 *
 * @package frontend\widgets
 */
class ProductItem extends Widget
{
	/**
	 * @var CatalogProduct
	 */
	public $product;

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('product-item', [
			'product' => $this->product,
		]);
	}
}
