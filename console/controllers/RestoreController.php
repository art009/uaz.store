<?php

namespace console\controllers;

use backend\models\Menu;
use Yii;
use yii\console\Controller;

/**
 * Class RestoreController
 *
 * @package console\controllers
 */
class RestoreController extends Controller
{
	/**
	 * @return void
	 */
	public function actionIndex()
	{
		echo 'Контроллер для восстановления базового состояния компонентов приложения' . PHP_EOL;
	}

	/**
	 * Переустановка основного меню
	 */
	public function actionMenu()
	{
		$items = [
			[
				'title' => 'Товары',
				'link' => '/catalog',
				'controller_id' => 'catalog',
				'action_id' => 'index',
			],
			[
				'title' => 'О компании',
				'link' => '/about',
				'controller_id' => 'site',
				'action_id' => 'about',
			],
			[
				'title' => 'Оплата и доставка',
				'link' => '/delivery',
				'controller_id' => 'site',
				'action_id' => 'delivery',
			],
			[
				'title' => 'Отзывы',
				'link' => '/reviews',
				'controller_id' => 'reviews',
				'action_id' => null,
			],
		];

		Menu::deleteAll();

		foreach ($items as $item) {
			$menu = new Menu();
			$menu->setAttributes($item);
			$menu->save();
		}
	}
}
