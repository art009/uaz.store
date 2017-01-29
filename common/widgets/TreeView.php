<?php

namespace common\widgets;

use common\assets\TreeViewAsset;
use yii\helpers\Html;

/**
 * Tree view widget
 */
class TreeView extends \yii\bootstrap\Widget
{
	/**
	 * @var array
	 */
	public $data = [];

	/**
	 * @var bool
	 */
	public $showTags = true;

	/**
	 * @var bool
	 */
	public $enableLinks = true;

	/**
	 * @var string
	 */
	public $expandIcon = 'glyphicon glyphicon-chevron-right';

	/**
	 * @var string
	 */
	public $collapseIcon = 'glyphicon glyphicon-chevron-down';

	/**
	 * @var int
	 */
	public $levels = 99;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

		$this->clientOptions['data'] = $this->data;
		$this->clientOptions['showTags'] = $this->showTags;
		$this->clientOptions['enableLinks'] = $this->enableLinks;
		$this->clientOptions['expandIcon'] = $this->expandIcon;
		$this->clientOptions['collapseIcon'] = $this->collapseIcon;
		$this->clientOptions['levels'] = $this->levels;

		if (!empty($this->data)) {
			TreeViewAsset::register($this->getView());

			$this->registerPlugin('treeview');
		}
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
		if (!empty($this->data)) {
			echo Html::tag('div', '', ['id' => $this->getId()]);
		}
    }
}
