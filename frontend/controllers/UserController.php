<?php

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Class UserController
 *
 * @package frontend\controllers
 */
class UserController extends Controller
{
	/**
	 * Страница ЛК
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}
}
