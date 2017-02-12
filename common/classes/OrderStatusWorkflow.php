<?php

namespace common\classes;

use common\models\Order;
use common\models\OrderProduct;
use Yii;

/**
 * Class OrderStatusWorkflow
 *
 * @package common\classes
 */
class OrderStatusWorkflow
{
	/**
	 * @var Order
	 */
	protected $order;

	/**
	 * OrderStatusWorkflow constructor.
	 * @param Order $order
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	/**
	 * @return Order
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * Перевод в статус
	 *
	 * @param int $status
	 *
	 * @return bool
	 */
	public function toStatus($status)
	{
		$result = false;

		switch ($status) {
			case Order::STATUS_CART_CLEAR:
				$result = $this->transitionToCartClear();
				break;
		}

		return $result;
	}

	/**
	 * Перевод в статус "Очищенная корзина"
	 *
	 * @return bool
	 */
	public function transitionToCartClear()
	{
		$result = false;
		$order = $this->getOrder();
		if ($order->status == Order::STATUS_CART) {
			$connection = Yii::$app->db;
			$transaction = $connection->beginTransaction();
			try {
				OrderProduct::deleteAll('order_id = :orderId', [':orderId' => $order->id]);
				$order->status = Order::STATUS_CART_CLEAR;
				$order->sum = 0;
				if ($order->save()) {
					$transaction->commit();
					$result = true;
				} else {
					$transaction->rollBack();
				}
			} catch (\Exception $e) {
				$transaction->rollBack();
			}
		}

		return $result;
	}
}
