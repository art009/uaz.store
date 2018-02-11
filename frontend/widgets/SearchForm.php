<?php

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class SearchForm
 * @package frontend\widgets
 */
class SearchForm extends Widget
{
	/**
	 * @var string
	 */
	public $id = 'm-search-form';

	/**
	 * @var string
	 */
	public $action = '/site/search';

	/**
	 * @var string
	 */
	public $method = 'GET';

	/**
	 * @var array
	 */
	public $containerOptions = ['class' => 'm-search-form__cont col-md-6 col-sm-12'];

	/**
	 * @var array
	 */
	public $inputOptions = [];

	/**
	 * @var array
	 */
	public $buttonOptions = [];

	/**
	 * @var null|string
	 */
	public $query = null;

	/**
	 * @var string
	 */
	private $input;

	/**
	 * @var string
	 */
	private $button;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->initInput();
		$this->initButton();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo Html::beginTag('div', $this->containerOptions);
			echo Html::beginForm($this->action, $this->method, ['id' => $this->id]);

				echo Html::beginTag('div', ['class' => 'input-group']);
					echo $this->input;
					echo Html::beginTag('div', ['class' => 'input-group-btn']);
						echo $this->button;
					echo  Html::endTag('div');
				echo Html::endTag('div');

			echo Html::endForm();
		echo Html::endTag('div');

		parent::run();
	}

	/**
	 * Initial input
	 * @return void
	 */
	protected function initInput()
	{
		$this->inputOptions['class'] = isset($this->inputOptions['class'])
			? $this->inputOptions['class']
			: 'form-control';
		$this->inputOptions['placeholder'] = isset($this->inputOptions['placeholder'])
			? $this->inputOptions['placeholder']
			: 'Поиск...';

		$this->input = Html::textInput('q', $this->query, $this->inputOptions);
	}

	/**
	 * Initial button
	 * @return void
	 */
	protected function initButton()
	{
		$this->buttonOptions['class'] = isset($this->buttonOptions['class'])
			? $this->buttonOptions['class']
			: 'btn btn-default';

		$this->button = Html::button('<i class="glyphicon glyphicon-search"></i>', $this->buttonOptions);
	}
}