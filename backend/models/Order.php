<?php

namespace backend\models;

/**
 * Class Order
 *
 * @package backend\models
 */
class Order extends \common\models\Order
{
	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			// Физически не удаляем
			return false;
		} else {
			return false;
		}
	}
}
