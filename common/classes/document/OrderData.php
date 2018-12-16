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
		'userIndividual' => [
			'phone',
			'name',
			'passport_series',
			'passport_number',
			'address',
		],
        'userLegal' => [
            'phone',
            'name',
            'inn',
            'kpp',
            'address',
        ],
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
		    if ($user->isLegal()) {
                $fields = $this->requiredFields['userLegal'] ?? [];
            } else {
                $fields = $this->requiredFields['userIndividual'] ?? [];
            }
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
			$result['userAddress'] = $user->address ?? null;
			if ($user->isLegal()) {
                $result['userInn'] = $user->inn ?? null;
                $result['userKpp'] = $user->kpp ?? null;
            } else {
                $result['userPassport'] = ($user->passport_series && $user->passport_number) ? $user->passport_series . ' ' . $user->passport_number : null;
            }
		}
		$k = 0;
		if ($products) {
			foreach ($products as $product) {
				$k++;
				$result['orderProductNum'][$k] = $k;
				$result['orderProductTitle'][$k] = $product->getTitle();
				$result['orderProductCount'][$k] = $product->quantity;
				$result['orderProductUnit'][$k] = $product->product ? $product->product->unit : null;
				$result['orderProductPrice'][$k] = $product->price;
				$result['orderProductSum'][$k] = $product->getTotal();
				$result['orderProductCode'][$k] = $product->getCode();
			}
		}

		if ($order->delivery_sum) {
			$k++;
			$result['orderProductNum'][$k] = $k;
			$result['orderProductTitle'][$k] = 'Доставка';
			$result['orderProductCount'][$k] = 1;
			$result['orderProductUnit'][$k] = null;
			$result['orderProductPrice'][$k] = $order->delivery_sum;
			$result['orderProductSum'][$k] = $order->delivery_sum;
			$result['orderProductCode'][$k] = null;
		}

		return $result;
	}
}
