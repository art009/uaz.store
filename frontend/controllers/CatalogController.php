<?php

namespace frontend\controllers;

use common\models\CatalogCategory;
use Yii;
use common\components\PriceList;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class CatalogController
 *
 * @package frontend\controllers
 */
class CatalogController extends Controller
{
	/**
	 * Страница категорий товаров
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public function actionIndex(int $id = null)
	{
		$categories = CatalogCategory::findAll(['parent_id' => $id]);
		$category = $id ? $this->findCategory($id) : null;

		return $this->render('index', [
			'category' => $category,
			'children' => $categories,
			'id' => $id,
		]);
	}

	/**
	 * Страница категорий товаров
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public function actionView(int $id = null)
	{
		$category = $this->findCategory($id);

		return $this->render('view', [
			'category' => $category,
		]);
	}

	/**
	 * Страница поиска
	 *
	 * @param string|null $q
	 *
	 * @return string
	 */
	public function actionSearch($q = null)
	{
		return $this->render('search', [
			'q' => $q,
		]);
	}

	/**
	 * Генерация и выдача прайс-листа
	 */
	public function actionPriceList()
	{
		if (PriceList::check()) {
			return Yii::$app->response->sendFile(PriceList::filename(), 'uaz-store-price-list.xls');
		} else {
			Yii::$app->session->addFlash('warning', 'В данный момент прайс-лист недоступен! Попробуйте позже.');

			return $this->redirect(Yii::$app->request->referrer);
		}
	}

	/**
	 * @param int $id
	 *
	 * @return CatalogCategory
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findCategory(int $id)
	{
		$model = CatalogCategory::findOne($id);
		if (!$model) {
			throw new NotFoundHttpException('Категория не найдена.');
		}
		return $model;
	}
}
