<?php

namespace common\classes;

/**
 * Class OrderRejectReason
 *
 * @package common\classes
 */
class OrderRejectReason
{
	// Причины отказа
	const REJECT_DECLINED 	= 0;
	const REJECT_PRICE 		= 1;
	const REJECT_REST 		= 2;
	const REJECT_PAYMENT 	= 3;
	const REJECT_GATHERING	= 4;
	const REJECT_SENDING 	= 5;

	/**
	 * Список причин
	 *
	 * @var array
	 */
	static $reasonList = [
		self::REJECT_DECLINED 	=> 'Отказ без причины',
		self::REJECT_PRICE 		=> 'Не устроила цена',
		self::REJECT_REST 		=> 'Не оказалось в наличии',
		self::REJECT_PAYMENT 	=> 'Не удалось оплатить',
		self::REJECT_GATHERING	=> 'Не удалось собрать',
		self::REJECT_SENDING 	=> 'Не удалось отправить',
	];
}
