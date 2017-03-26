<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap\Modal;

/**
 * Class QuestionWidget
 * 
 * @package frontend\widgets
 */
class QuestionWidget extends Modal
{
	/**
	 * @var string
	 */
	public $header = 'Задать вопрос';

	/**
	 * @var string
	 */
	public $size = 'modal-fm';

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo Yii::$app->controller->run('form/question');

		return parent::run();
	}

	/**
	 * @inheritdoc
	 */
	public function getId($autoGenerate = true)
	{
		return 'question-form-modal';
	}
}
