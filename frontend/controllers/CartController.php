<?php

namespace frontend\controllers;

use common\models\CatalogProduct;
use yii\web\Controller;
use Yii;

/**
 * Class CartController
 *
 * @package frontend\controllers
 */
class CartController extends Controller
{
	/**
	 * Страница корзины
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index', [
			'cart' => Yii::$app->cart
		]);
	}

	/**
	 * Добавление товара
	 *
	 * @param int $productId
	 * @param int $quantity
	 *
	 * @return \yii\web\Response
	 */
	public function actionAdd($productId, $quantity = 1)
	{
		$product = CatalogProduct::findOne((int)$productId);
		if ($product) {
			$quantity = (int)$quantity;
			if ($quantity > 0 && $quantity < 100) {
				if (Yii::$app->cart->add($product->id, $quantity)) {
					Yii::$app->session->addFlash('success', 'Товар добавлен в корзину');
				} else {
					Yii::$app->session->addFlash('danger', 'Неудачная попытка добавления товара в корзину');
				}
			} else {
				Yii::$app->session->addFlash('danger', 'Недопустимое количество товара. Можно от 1 до 99.');
			}
		} else {
			Yii::$app->session->addFlash('danger', 'Товар не найден');
		}

		//$this->goBack();
		return $this->redirect('index');
	}

	/**
	 * Очистка корзины
	 *
	 * @return \yii\web\Response
	 */
	public function actionClear()
	{
		Yii::$app->cart->clear();

		return $this->redirect('index');
	}
}
