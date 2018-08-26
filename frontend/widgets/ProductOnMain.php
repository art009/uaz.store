<?php

namespace frontend\widgets;

use common\components\AppHelper;
use common\models\CatalogProduct;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class ProductOnMain
 *
 * Виджет товаров на главной
 *
 * @package frontend\widgets
 */
class ProductOnMain extends Widget
{
	const LIMIT = 4;

	/**
	 * @var CatalogProduct[]
	 */
	protected $products = [];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->products = CatalogProduct::getDb()->cache(function(){
			return CatalogProduct::find()
				->select(['id', 'title', 'link', 'price', 'image', 'oversize'])
				->where([
					'hide' => AppHelper::NO,
					'on_main' => AppHelper::YES,
				])
				->orderBy('id')
				->all();
		}, 0, new TagDependency(['tags' => CatalogProduct::ON_MAIN_CACHE_TAG]));

		if ($this->products) {
			shuffle($this->products);
			$this->products = array_slice($this->products, 0, self::LIMIT, true);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('product-on-main', [
			'products' => $this->products,
		]);
	}
}
