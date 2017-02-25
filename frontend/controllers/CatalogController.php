<?php

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Class CatalogController
 *
 * @package frontend\controllers
 */
class CatalogController extends Controller
{
	/**
	 * Страница товаров
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
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
	 * Страница справочников
	 *
	 * @return string
	 */
	public function actionManual()
	{
		return $this->render('manual');
	}

	/**
	 * Генерация и выдача прайс-листа
	 *
	 * @return string
	 */
	public function actionPriceList()
	{
		return $this->redirect('/');
	}
}
