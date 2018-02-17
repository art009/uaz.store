<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap\Modal;

/**
 * Class FastOrderWidget
 * 
 * @package frontend\widgets
 */
class FastOrderWidget extends Modal
{
	/**
	 * @var string
	 */
	public $header = 'Быстрый заказ';

	/**
	 * @var string
	 */
	public $size = 'modal-fm';

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo Yii::$app->controller->run('form/order');

		return parent::run();
	}

	/**
	 * @inheritdoc
	 */
	public function getId($autoGenerate = true)
	{
		return 'order-form-modal';
	}
}
