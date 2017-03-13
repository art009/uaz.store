<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap\Modal;

/**
 * Class CallbackWidget
 * 
 * @package frontend\widgets
 */
class CallbackWidget extends Modal
{
	/**
	 * @var string
	 */
	public $header = 'Заказ обратного звонка';

	/**
	 * @var string
	 */
	public $size = 'modal-fm';

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo Yii::$app->controller->run('form/callback');

		return parent::run();
	}

	/**
	 * @inheritdoc
	 */
	public function getId($autoGenerate = true)
	{
		return 'callback-form-modal';
	}
}
