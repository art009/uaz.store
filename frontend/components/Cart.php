<?php

namespace frontend\components;

use common\classes\CookieCart;
use common\classes\SessionCart;
use common\classes\UserCart;
use common\interfaces\CartProductInterface;
use yii\base\Component;
use Yii;

/**
 * Class Cart
 * @package frontend\components
 *
 * @property boolean $isTemp
 * @property boolean $isEmpty
 * @property int $quantity
 * @property int $sum
 */
class Cart extends Component
{
	/**
	 * @var SessionCart|UserCart
	 */
	protected $cart = null;

	/**
	 * Возвращает корзину
	 *
	 * @return SessionCart|UserCart
	 */
	protected function getCart()
	{
		if ($this->cart === null) {
			$cookieCart = new CookieCart();
			if (Yii::$app->user->isGuest) {
				$cart = new SessionCart();
				if ($cookieCart->getIdentityId() != $cart->getIdentityId()) {
					if ($cart->merge($cookieCart->getProducts())) {
						$cookieCart->clear();
						$cookieCart::updateIdentityId($cart->getIdentityId());
						$cart->reload();
					}
				}
			} else {
				$cart = new UserCart();
				if ($cart->merge($cookieCart->getProducts())) {
					$cookieCart->clear();
					$cart->reload();
				}
			}

			$this->cart = $cart;
		}

		return $this->cart;
	}

	/**
	 * Инициализация компонента корзины
	 */
	public function init()
	{
		$this->getCart();
	}

	/**
	 * Возвращает признак временной корзины
	 */
	public function getIsTemp()
	{
		return !($this->getCart() instanceof UserCart);
	}

	/**
	 * Возвращает признак пустой корзины
	 */
	public function getIsEmpty()
	{
		return $this->getCart()->getIsEmpty();
	}

	/**
	 * @return CartProductInterface[]
	 */
	public function getProducts()
	{
		return $this->getCart()->getProducts();
	}

	/**
	 * Добавление товара в корзину
	 *
	 * @param $productId
	 * @param $quantity
	 *
	 * @return bool
	 */
	public function add($productId, $quantity)
	{
		return $this->getCart()->addProduct($productId, $quantity);
	}

	/**
	 * Очистка корзины
	 *
	 * @return int
	 */
	public function clear()
	{
		return $this->getCart()->clear();
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
		return $this->getCart()->getQuantity($productId);
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
		return $this->getCart()->getSum($productId);
	}

	/**
	 * Добавление единицы товара в корзине
	 *
	 * @param $productId
	 *
	 * @return bool
	 */
	public function inc($productId)
	{
		$result = 0;
		$cartProduct = $this->getCart()->getCartProduct($productId);
		if ($cartProduct && $cartProduct->getQuantity() < 99) {
			$quantity = $cartProduct->getQuantity() + 1;
			if ($cartProduct->updateQuantity($quantity)) {
				$result = $quantity;
			}
		}

		return $result;
	}

	/**
	 * Убавление единицы товара в корзине
	 *
	 * @param $productId
	 *
	 * @return bool
	 */
	public function dec($productId)
	{
		$result = 0;
		$cartProduct = $this->getCart()->getCartProduct($productId);
		if ($cartProduct && $cartProduct->getQuantity() > 1) {
			$quantity = $cartProduct->getQuantity() - 1;
			if ($cartProduct->updateQuantity($quantity)) {
				$result = $quantity;
			}
		}

		return $result;
	}

	/**
	 * Перезагрузка корзины
	 */
	public function reload()
	{
		$this->getCart()->reload();

		return $this;
	}

	/**
	 * Удаление товара из корзины
	 *
	 * @param $productId
	 *
	 * @return bool
	 */
	public function remove($productId)
	{
		$result = false;
		$cartProduct = $this->getCart()->getCartProduct($productId);
		if ($cartProduct && $cartProduct->remove()) {
			$result = true;
		}

		return $result;
	}

}
