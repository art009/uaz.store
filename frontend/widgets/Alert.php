<?php

namespace frontend\widgets;

use yii\bootstrap\Html;

/**
 * Class Alert
 *
 * @package frontend\widgets
 */
class Alert extends \common\widgets\Alert
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		echo Html::beginTag('div', ['id' => 'alert-container']);
		parent::init();
		echo Html::endTag('div');

		echo Html::beginTag('script', ['id' => 'alert-template', 'type' => 'text/x-handlebars-template']);
		$buttonHtml = Html::tag('button', '&times;', [
			'type' => 'button',
			'class' => 'close',
			'data-dismiss' => 'alert',
			'aria-hidden' => 'true',
		]);
		echo Html::tag('div', $buttonHtml . '{{{body}}}', [
			'id' => $this->getId() . '-{{type}}-{{k}}',
			'class' => 'alert-{{type}} alert fade in',
		]);
		echo Html::endTag('script');
	}
}
