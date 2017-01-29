<?php

namespace common\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use nex\chosen\ChosenBootstrapAsset;

/**
 * Chosen renders a Chosen select (Harvest Chosen jQuery plugin).
 *
 * @see http://harvesthq.github.io/chosen
 */
class ChosenSelect extends InputWidget
{
    /**
     * @var boolean whether to render input as multiple select
     */
    public $multiple = false;

    /**
     * @var boolean whether to render hidden input for empty value
     */
    public $empty = false;

    /**
     * @var boolean whether to show deselect button on single select
     */
    public $allowDeselect = true;

    /**
     * @var integer|boolean hide the search input on single selects if there are fewer than (n) options or disable at all if set to true
     */
    public $disableSearch = 8;

    /**
     * @var string placeholder text
     */
    public $placeholder = null;

	/**
	 * @var string no_results_text text
	 */
	public $noResultsText = 'Ничего не найдено';

    /**
     * @var string category for placeholder translation
     */
    public $translateCategory = 'app';

    /**
     * @var array items array to render select options
     */
    public $items = [];

    /**
     * @var array options for Chosen plugin
     * @see http://harvesthq.github.io/chosen/options.html
     */
    public $clientOptions = [
		'search_contains' => true,
		'single_backstroke_delete' => false,
	];

    /**
     * @var array event handlers for Chosen plugin
     * @see http://harvesthq.github.io/chosen/options.html#triggered-events
     */
    public $clientEvents = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->multiple) {
            $this->options['multiple'] = true;
        } elseif ($this->allowDeselect) {
            $this->items = ArrayHelper::merge([null => ''], $this->items);
            $this->clientOptions['allow_single_deselect'] = true;
        }
        if ($this->disableSearch === true) {
            $this->clientOptions['disable_search'] = true;
        } else {
            $this->clientOptions['disable_search_threshold'] = $this->disableSearch;
        }
        $this->clientOptions['placeholder_text_single'] = \Yii::t($this->translateCategory, $this->placeholder ? $this->placeholder : 'Select an option');
        $this->clientOptions['placeholder_text_multiple'] = \Yii::t($this->translateCategory, $this->placeholder ? $this->placeholder : 'Select some options');
		$this->clientOptions['no_results_text'] = \Yii::t($this->translateCategory, $this->noResultsText ? $this->noResultsText : 'No results match');

        $this->options['unselect'] = null;

		$this->options = ArrayHelper::merge(['class' => 'has-chosen form-control'], $this->options);

        $this->registerScript();
        $this->registerEvents();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            if ($this->empty) {
                echo Html::activeHiddenInput($this->model, $this->attribute, ['id' => strtolower($this->model->formName()) . '-' . $this->attribute . '-empty', 'value' => '']);
            }
            echo Html::activeListBox($this->model, $this->attribute, $this->items, $this->options);
        } else {
            if ($this->empty) {
                echo Html::hiddenInput($this->name, $this->value, ['id' => $this->name . '-empty', 'value' => '']);
            }
            echo Html::listBox($this->name, $this->value, $this->items, $this->options);
        }
    }

    /**
     * Registers chosen.js
     */
    public function registerScript()
    {
		ChosenBootstrapAsset::register($this->getView());
        $clientOptions = Json::encode($this->clientOptions);
        $id = $this->options['id'];
        $this->getView()->registerJs("jQuery('#$id').chosen({$clientOptions});");
    }
    /**
     * Registers Chosen event handlers
     */
    public function registerEvents()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $handle = new JsExpression($handle);
                $js[] = "jQuery('#{$this->options['id']}').on('{$event}', {$handle});";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }
}
