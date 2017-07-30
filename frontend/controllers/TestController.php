<?php

namespace frontend\controllers;

use common\models\CatalogCategory;
use common\models\Manual;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class TestController
 *
 * @package frontend\controllers
 */
class TestController extends Controller
{
	/**
	 * Страница каталогов
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		echo '33';
	}
}
