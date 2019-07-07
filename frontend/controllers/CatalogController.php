<?php

namespace frontend\controllers;

use common\components\PriceList;
use common\models\CatalogCategory;
use common\models\CatalogProduct;
use Yii;
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
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionView(int $id = null)
	{
		$category = $this->findCategory($id);

		return $this->render('view', [
			'category' => $category,
			'products' => $category->getFrontProducts(),
		]);
	}

	/**
	 * Страница поиска
	 *
	 * @return string
	 */
	public function actionSearch()
	{
		return $this->render('search');
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

	/**
	 * @param int $id
	 *
	 * @return CatalogProduct
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findProduct(int $id)
	{
		$model = CatalogProduct::findOne($id);
		if (!$model || $model->isHidden()) {
			throw new NotFoundHttpException('Товар не найден.');
		}
		return $model;
	}

	/**
	 * Страница товара
	 *
	 * @param int $id
	 * @param int $categoryId
	 *
	 * @return string
	 */
	public function actionProduct(int $id = null, int $categoryId = null)
	{
		$product = $this->findProduct($id);
		$category = $this->findCategory($categoryId);

		return $this->render('product', [
			'product' => $product,
			'category' => $category,
		]);
	}

	public function actionSimilar(int $id = null)
    {
        $product = $this->findProduct($id);
        return $this->render('similar', [
            'product' => $product,
        ]);
    }
}
