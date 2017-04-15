<?php

namespace frontend\controllers;

use common\models\CatalogManual;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
	 * @param $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionView($id)
	{
		$model = CatalogManual::findOne($id);
		if (!$model) {
			throw new NotFoundHttpException('Каталог не найден.');
		}

		return $this->render('view', [
			'model' => $model,
		]);
	}
}
