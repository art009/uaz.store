<?php

namespace common\classes;

use common\interfaces\CartProductInterface;
use Yii;

/**
 * Class AbstractCart
 * @package common\classes
 *
 * @property mixed $identityId
 * @property boolean $isEmpty
 */
abstract class AbstractCart
{
	/**
	 * @string Идентификатор сущности корзины
	 */
	protected $identityId = null;

	/**
	 * @var array|null
	 */
	protected $products = null;

	/**
	 * Поиск идентификатора сущности корзины
	 *
	 * @return string
	 */
	abstract public function findIdentityId();

	/**
	 * Загрузка продуктов корзины
	 *
	 * @return CartProductInterface[]
	 */
	abstract public function load();

	/**
	 * Очистка корзины
	 *
	 * @return int
	 */
	abstract public function clear();

	/**
	 * Добавление в корзину
	 *
	 * @param string $identityId
	 * @param integer $productId
	 * @param integer $quantity
	 *
	 * @return boolean
	 */
	abstract protected function add($identityId, $productId, $quantity = 1);

	/**
	 * Возвращает идентификатор сущности корзины
	 *
	 * @return string|null
	 */
	public function getIdentityId()
	{
		if ($this->identityId === null) {
			$this->identityId = $this->findIdentityId();
		}

		return $this->identityId;
	}

	/**
	 * Возвращает продукты корзины
	 *
	 * @return CartProductInterface[]
	 */
	public function getProducts()
	{
		if (!is_array($this->products)) {
			$this->reload();
		}

		return $this->products;
	}

	/**
	 * Возвращает признак пустой корзины
	 *
	 * @inheritdoc
	 */
	public function getIsEmpty()
	{
		$products = $this->getProducts();

		return !is_array($products) || (count($products) == 0);
	}

	/**
	 * Возвращает идентификаторы товаров
	 *
	 * @return array
	 */
	public function getProductIds()
	{
		$result = [];
		foreach ($this->getProducts() as $product) {
			$result[] = $product->getProductId();
		}

		return $result;
	}

	/**
	 * Возвращает товар в корзине
	 *
	 * @param integer $productId
	 *
	 * @return CartProductInterface|null
	 */
	public function getCartProduct($productId)
	{
		$result = null;
		foreach ($this->getProducts() as $product) {
			if ($product->getProductId() == $productId) {
				$result = $product;
			}
		}

		return $result;
	}

	/**
	 * Перезагрузка товаров в корзине
	 */
	public function reload()
	{
		$this->products = $this->load();
	}

	/**
	 * @inheritdoc
	 */
	public function addProduct($productId, $quantity)
	{
		if ($cartProduct = $this->getCartProduct($productId)) {
			return $cartProduct->updateQuantity($cartProduct->getQuantity() + $quantity);
		} else {
			return $this->add($this->getIdentityId(), $productId, $quantity);
		}
	}

	/**
	 * Склеивание корзин
	 *
	 * @param CartProductInterface[] $products
	 *
	 * @return bool
	 */
	public function merge($products)
	{
		$result = true;
		if ($products) {
			$connection = Yii::$app->db;
			$transaction = $connection->beginTransaction();
			try {
				foreach ($products as $product) {
					$this->addProduct($product->getProductId(), $product->getQuantity());
				}
				$transaction->commit();
			} catch (\Exception $e) {
				$transaction->rollBack();
				$result = false;
			}
		}

		return $result;
	}

	/**
	 * Возвращает количество товаров в корзине
	 *
	 * @param int|null $productId
	 *
	 * @return int
	 */
	public function getQuantity($productId = null)
	{
		$result = 0;
		if ($productId && $cartProduct = $this->getCartProduct($productId)) {
			$result = $cartProduct->getQuantity();
		} else {
			foreach ($this->getProducts() as $product) {
				$result += $product->getQuantity();
			}
		}

		return $result;
	}

	/**
	 * Возвращает стоимость товаров в корзине
	 *
	 * @param int|null $productId
	 *
	 * @return int
	 */
	public function getSum($productId = null)
	{
		$result = 0;
		if ($productId && $cartProduct = $this->getCartProduct($productId)) {
			$result = round($cartProduct->getPrice() * $cartProduct->getQuantity(), 2);
		} else {
			foreach ($this->getProducts() as $product) {
				$result += round($product->getPrice() * $product->getQuantity(), 2);
			}
		}

		return $result;
	}
}
