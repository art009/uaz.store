<?php

namespace common\classes;

use common\models\Order;
use common\models\OrderProduct;
use Yii;

/**
 * Class UserCart
 *
 * @package common\classes
 */
class UserCart extends AbstractCart
{
	/**
	 * @var Order|null
	 */
	protected $order = null;

	/**
	 * Возвращает заказ для корзины
	 *
	 * @param boolean|false $create
	 *
	 * @return Order|null
	 */
	public function getOrder($create = false)
	{
		if ($this->order === null) {
			$this->order = Order::create(Yii::$app->user->id, $create);
		}

		return $this->order;
	}

	/**
	 * @inheritdoc
	 *
	 * @param bool|false $createOrder
	 */
	public function findIdentityId($createOrder = false)
	{
		return $this->getOrder($createOrder) ? $this->getOrder()->id : null;
	}

	/**
	 * @inheritdoc
	 *
	 * @param bool|false $createOrder
	 */
	public function load($createOrder = false)
	{
		return $this->getOrder($createOrder) ? $this->getOrder()->getOrderProducts()->all() : [];
	}

	/**
	 * @inheritdoc
	 */
	public function clear()
	{
	    $currentOrder = $this->getOrder();
	    if ($currentOrder->status > Order::STATUS_CART_CLEAR) {
	        $newOrder = $this->getOrder(true);
            return $newOrder->getWorkflow()->toStatus(Order::STATUS_CART_CLEAR);
        }
	    return false;
	}

	/**
	 * @inheritdoc
	 */
	protected function add($identityId, $productId, $quantity = 1)
	{
		$identityId = $identityId ?: $this->findIdentityId(true);

		$orderProduct = new OrderProduct();
		$orderProduct->order_id = $identityId;
		$orderProduct->product_id = $productId;
		$orderProduct->quantity = $quantity;

		return $orderProduct->save();
	}
}
