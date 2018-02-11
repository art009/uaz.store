<?php

namespace frontend\widgets;

use common\models\CatalogProduct;
use Yii;
use yii\base\Widget;

/**
 * Class ProductSearch
 *
 * @package frontend\widgets
 */
class ProductSearch extends Widget
{
	/**
	 * @var string
	 */
	public $placeholder = 'Введите название или артикул';

	/**
	 * @var string
	 */
	public $text = '';

	/**
	 * @var CatalogProduct[]
	 */
	protected $products = [];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$query = $this->getQuery();
		if ($query) {
			try {
				$ids = $this->getProductIds($query);
				if ($ids) {
					$this->setProducts(CatalogProduct::findAll($ids));
				} else {
					$this->setText('Товары не найдены.');
				}
			} catch (\Exception $e) {
				$this->setText('Поиск товаров недоступен.');
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('product-search', [
			'products' => $this->getProducts(),
			'query' => $this->getQuery(),
			'text' => $this->getText(),
		]);
	}

	/**
	 * @return \WebApplication
	 */
	protected function getApp()
	{
		return Yii::$app;
	}

	/**
	 * @return string
	 */
	protected function getQuery()
	{
		$request = $this->getApp()->getRequest();

		return (string)$request->getQueryParam('q');
	}

	/**
	 * @param string $q
	 *
	 * @return int[]
	 */
	protected function getProductIds(string $q)
	{
		$search = $this->getApp()->sphinxSearch;

		return $search->getIds($q);
	}

	/**
	 * @param string $placeholder
	 */
	public function setPlaceholder(string $placeholder)
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * @param string $text
	 */
	public function setText(string $text)
	{
		$this->text = $text;
	}

	/**
	 * @param CatalogProduct[] $products
	 */
	public function setProducts(array $products)
	{
		$this->products = $products;
	}

	/**
	 * @return CatalogProduct[]
	 */
	public function getProducts(): array
	{
		return $this->products;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder(): string
	{
		return $this->placeholder;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}
}
