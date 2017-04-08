<?php

namespace frontend\controllers;

use common\models\CatalogProduct;
use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Class OrderController
 *
 * @package frontend\controllers
 */
class OrderController extends Controller
{
	/**
	 * Создание заказа
	 *
	 * @return string
	 */
	public function actionCreate()
	{
		Yii::$app->session->addFlash('warning', 'Создание заказа пока недоступно.');
		return $this->redirect(Yii::$app->request->referrer);
	}
}
