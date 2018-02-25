<?php

namespace common\components;

use WebApplication;
use Yii;
use yii\console\Application;

/**
 * Trait AppComponentTrait
 *
 * @package common\components
 */
trait AppComponentTrait
{
	/**
	 * @return WebApplication | Application
	 */
	public function getApp()
	{
		return Yii::$app;
	}
}
