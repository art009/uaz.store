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
			$this->order = Order::find()
				->byStatus([Order::STATUS_CART])
				->byUserId(Yii::$app->user->id)
				->joinWith(['orderProducts'])
				->one();
		}
		if ($this->order === null && $create == true) {
			$order = new Order();
			$order->user_id = Yii::$app->user->id;
			$order->status = Order::STATUS_CART;
			$this->order = $order->save() ? $order : null;
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
		return $this->getOrder() ? $this->getOrder()->getWorkflow()->toStatus(Order::STATUS_CART_CLEAR) : false;
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
