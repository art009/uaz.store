<?php

namespace common\classes;

use Yii;
use common\models\Cart;

/**
 * Class SessionCart
 * 
 * @package common\classes
 */
class SessionCart extends AbstractCart
{
	/**
	 * @inheritdoc
	 */
	public function findIdentityId()
	{
		$session = Yii::$app->session;
		if ($session->isActive == false) {
			$session->open();
		}

		return $session->getId();
	}

	/**
	 * @return Cart[]
	 */
	public function load()
	{
		$products = Cart::find()
			->byIdentityId($this->getIdentityId())
			->joinWith(['product'])
			->all();

		return $products;
	}

	/**
	 * @inheritdoc
	 */
	protected function add($identityId, $productId, $quantity = 1)
	{
		$model = new Cart();
		$model->identity_id = (string)$identityId;
		$model->product_id = (int)$productId;
		$model->quantity = (int)$quantity;

		return $model->save();
	}

	/**
	 * @inheritdoc
	 */
	public function clear()
	{
		return Cart::deleteAll('identity_id = :identityId', [':identityId' => $this->getIdentityId()]);
	}
}
