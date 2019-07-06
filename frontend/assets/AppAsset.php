<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
	    'js/fancybox/jquery.fancybox.css',
    ];
    public $js = [
	    'js/mustache.js',
    	'js/main.js',
	    'js/fancybox/jquery.fancybox.pack.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
		'yii\jui\JuiAsset',
        'yii\web\JqueryAsset'
    ];
}
