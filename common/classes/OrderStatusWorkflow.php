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
	 * Список доступных статусов
	 *
	 * @param int $status
	 *
	 * @return array
	 */
	public static function statusList($status)
	{
		$result = [];
		switch ($status) {
			case Order::STATUS_CART:
				$result = [
					Order::STATUS_CART_CLEAR,
					Order::STATUS_PICKUP,
					Order::STATUS_PROCESS,
				];
				break;
			case Order::STATUS_CART_CLEAR:
				$result = [
					Order::STATUS_CART,
				];
				break;
			case Order::STATUS_PICKUP:
				$result = [
					Order::STATUS_TRANSFER,
					Order::STATUS_REJECT,
				];
				break;
			case Order::STATUS_PROCESS:
				$result = [
					Order::STATUS_PAYMENT_WAITING,
					Order::STATUS_REJECT,
				];
				break;
			case Order::STATUS_PAYMENT_WAITING:
				$result = [
					Order::STATUS_PAYMENT_DONE,
					Order::STATUS_PAYMENT_PROCESS,
					Order::STATUS_GATHERING,
					Order::STATUS_REJECT,
				];
				break;
			case Order::STATUS_PAYMENT_DONE:
				$result = [
					Order::STATUS_PAYMENT_PROCESS,
					Order::STATUS_GATHERING,
					Order::STATUS_REJECT,
				];
				break;
			case Order::STATUS_PAYMENT_PROCESS:
				$result = [
					Order::STATUS_GATHERING,
					Order::STATUS_REJECT,
				];
				break;
			case Order::STATUS_GATHERING:
				$result = [
					Order::STATUS_SENDING,
					Order::STATUS_REJECT,
				];
				break;
			case Order::STATUS_SENDING:
				$result = [
					Order::STATUS_DONE,
					Order::STATUS_REJECT,
				];
				break;
		}

		return $result;
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
