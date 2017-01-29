<?php

namespace common\assets;

/**
 * Class TreeViewAsset
 *
 * @package common\assets
 */
class TreeViewAsset extends CommonAsset
{
	public $css = [
		'css/bootstrap-treeview.css',
	];
	public $js = [
		'js/bootstrap-treeview.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
