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
 * @property \yii\sphinx\Connection $sphinx
 * @property \common\components\SphinxSearch $sphinxSearch
 * @property common\components\cashbox\Cashbox $cashbox
 */
class WebApplication extends yii\web\Application
{
}
