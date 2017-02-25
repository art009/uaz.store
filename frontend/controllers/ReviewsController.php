<?php

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Class ReviewsController
 *
 * @package frontend\controllers
 */
class ReviewsController extends Controller
{
	/**
	 * Страница отзывов
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}
}
