<?php

namespace common\classes\document;

use common\models\Order;
use common\models\User;

/**
 * Class OrderObject
 *
 * Базовый класс для классов работающих с заказом
 *
 * @package common\classes\document
 */
class OrderObject
{
	/**
	 * @var Order
	 */
	protected $order;

	/**
	 * OrderManager constructor.
	 *
	 * @param Order $order
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	/**
	 * @return Order
	 */
	protected function getOrder(): Order
	{
		return $this->order;
	}

	/**
	 * @return User
	 */
	protected function getUser(): User
	{
		return $this->getOrder()->user;
	}

	/**
	 * @return int
	 */
	protected function getOrderId(): int
	{
		return (int)$this->getOrder()->id;
	}

	/**
	 * @return int
	 */
	protected function getUserId(): int
	{
		return (int)$this->getOrder()->user_id;
	}

	/**
	 * @return bool
	 */
	protected function isUserLegal(): bool
	{
		$user = $this->getUser();

		return $user->isLegal();
	}

	/**
	 * @param string $template
	 *
	 * @return string
	 */
	protected function getOrderDate(string $template = 'd.m.Y'): string
	{
		return date($template, strtotime($this->getOrder()->created_at));
	}
}
