<?php

namespace console\controllers;

use common\components\PriceList;
use yii\console\Controller;

/**
 * Class CommandController
 *
 * @package console\controllers
 */
class CommandController extends Controller
{
	/**
	 * @return void
	 */
	public function actionIndex()
	{
		echo 'Контроллер для базовых консольных команд' . PHP_EOL;
	}

	/**
	 * Генерация прайс-листа товаров
	 */
	public function actionPriceList()
	{
		PriceList::execute();
	}
}
