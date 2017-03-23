<?php
/**
 * Yii bootstrap file.
 */
class Yii extends \yii\BaseYii
{
	/**
	 * @var WebApplication the application instance
	 */
	public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(__DIR__ . '/../vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container();
/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property \common\components\ImageHandler $ih
 * @property \frontend\components\Cart $cart
 * @property \yii\redis\Cache $cache
 */
class WebApplication extends yii\web\Application
{
}
