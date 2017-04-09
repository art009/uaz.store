<?php

namespace frontend\controllers;

use common\models\CatalogManual;
use yii\web\Controller;

/**
 * Class ManualController
 *
 * @package frontend\controllers
 */
class ManualController extends Controller
{
	/**
	 * Страница каталогов
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		$models = CatalogManual::find()->all();

		return $this->render('index', [
			'models' => $models,
		]);
	}

	/**
	 * Страница справочника
	 *
	 * @return string
	 */
	public function actionView()
	{
		$model = CatalogManual::find()->all();

		return $this->render('view', [
			'model' => $model,
		]);
	}
}
