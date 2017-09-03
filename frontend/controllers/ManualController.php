<?php

namespace frontend\controllers;

use common\models\CatalogCategory;
use common\models\Manual;
use common\models\ManualCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;

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
		$models = Manual::find()->all();

		return $this->render('index', [
			'models' => $models,
		]);
	}

	/**
	 * Страница справочника
	 *
	 * @param int $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionView($id)
	{
		$model = Manual::findOne((int)$id);
		if (!$model) {
			throw new NotFoundHttpException('Каталог не найден.');
		}
		$categories = ManualCategory::findAll(['manual_id' => $model->id, 'parent_id' => null]);

		return $this->render('view', [
			'model' => $model,
			'categories' => $categories,
		]);
	}

	/**
	 * Страница категории справочника
	 *
	 * @param int $id
	 * @param int|null $categoryId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionCategory($id, $categoryId = null)
	{
		$model = Manual::findOne((int)$id);
		if (!$model) {
			throw new NotFoundHttpException('Каталог не найден.');
		}
		$category = ManualCategory::findOne((int)$categoryId);
		if (!$model) {
			throw new NotFoundHttpException('Категория не найдена.');
		}
		$categories = $category->manualCategories;

		return $this->render('category', [
			'model' => $model,
			'category' => $category,
			'categories' => $categories,
		]);
	}

	/**
	 * Страница категории справочника с картинкой
	 *
	 * @param int $id
	 * @param int|null $categoryId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionImage($id, $categoryId = null)
	{
		$model = Manual::findOne((int)$id);
		if (!$model) {
			throw new NotFoundHttpException('Каталог не найден.');
		}
		$category = ManualCategory::findOne((int)$categoryId);
		if (!$model) {
			throw new NotFoundHttpException('Категория не найдена.');
		}
		if (!$model->image) {
			throw new NotFoundHttpException('Категория без чертежа.');
		}

		$dataProvider = new ArrayDataProvider([
			'allModels' => $category->manualProducts,
			'pagination' => false,
		]);

		return $this->render('image', [
			'dataProvider' => $dataProvider,
			'model' => $model,
			'category' => $category,
		]);
	}
}
