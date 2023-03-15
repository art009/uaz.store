<?php

namespace common\classes;

/**
 * Class OrderPayment
 *
 * @package common\classes
 */
class OrderPayment
{
	public static function inlineForm(int $clientId, int $orderId, float $sum, string $phone): string
	{
		$data = http_build_query([
			'clientid' => $clientId,
			'orderid' => $orderId,
			'sum' => $sum,
			'client_phone' => $phone,
		]);

		$options = [
			'http' => [
				'method' => 'POST',
				'header' => 'Content-type: application/x-www-form-urlencoded',
				'content' => $data,
			],
		];

		$context = stream_context_create($options);


		return file_get_contents('https://uaz.server.paykeeper.ru/order/inline/', false, $context);
	}
}
