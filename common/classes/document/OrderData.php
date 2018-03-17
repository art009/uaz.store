<?php

namespace common\classes\document;

/**
 * Class OrderData
 *
 * Сбор данных по заказу
 *
 * @package common\classes\document
 */
class OrderData extends OrderObject
{
	/**
	 * @var array
	 */
	protected $requiredFields = [
		'user' => [
			'phone',
			'name',
			'passport_series',
			'passport_number',
			'address',
		]
	];

	/**
	 * Валидация данных
	 *
	 * @return array
	 */
	public function validate(): array
	{
		$result = [];
		$user = $this->getUser();
		if ($user) {
			$fields = $this->requiredFields['user'] ?? [];
			foreach ($fields as $field) {
				if (!$user->getAttribute($field)) {
					$result[] = 'Не заполнено поле: ' . $user->getAttributeLabel($field);
				}
			}
		} else {
			$result[] = 'Недоступен пользователь по заказу';
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$order = $this->getOrder();
		$products = $order->orderProducts;
		$total = $order->getTotal();
		$totalStr = PriceHelper::number2string($total);
		$result = [
			'orderId' => $order->id,
			'orderDate' => $this->getOrderDate(),
			'orderTotalSum' => PriceHelper::priceFormat($total),
			'orderTotalCount' => count($products),
			'orderTotalSumString' => mb_strtoupper(mb_substr($totalStr, 0, 1)) . mb_substr($totalStr, 1),
		];
		$user = $this->getUser();
		if ($user) {
			$result['userPhone'] = $user->phone ? '8' . $user->phone : null;
			$result['userName'] = $user->name ?? null;
			$result['userPassport'] = ($user->passport_series && $user->passport_number) ? $user->passport_series . ' ' . $user->passport_number : null;
			$result['userAddress'] = $user->address ?? null;
		}
		if ($products) {
			foreach ($products as $num => $product) {
				$result['orderProductNum'][$num] = $num;
				$result['orderProductTitle'][$num] = $product->getTitle();
				$result['orderProductCount'][$num] = $product->quantity;
				$result['orderProductUnit'][$num] = $product->product ? $product->product->unit : null;
				$result['orderProductPrice'][$num] = $product->price;
				$result['orderProductSum'][$num] = $product->getTotal();
			}
		}

		return $result;
	}
}
