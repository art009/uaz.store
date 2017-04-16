<?php

namespace frontend\controllers;

use common\models\CatalogCategory;
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
	 * @param int $id
	 * @param int|null $categoryId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionView($id, $categoryId = null)
	{
		$model = CatalogManual::findOne((int)$id);
		if (!$model) {
			throw new NotFoundHttpException('Каталог не найден.');
		}
		$category = $categoryId ? CatalogCategory::findOne((int)$categoryId) : null;

		return $this->render('view', [
			'model' => $model,
			'category' => $category,
		]);
	}
}
