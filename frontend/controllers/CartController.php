<?php

namespace frontend\controllers;

use common\models\CatalogProduct;
use yii\web\Controller;
use Yii;
use yii\web\Response;

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
		$result = [
			'type' => 'danger',
			'message' => 'Неизвестная ошибка',
			'count' => Yii::$app->cart->getQuantity() + $quantity,
		];

		$product = CatalogProduct::findOne((int)$productId);
		if ($product) {
			$quantity = (int)$quantity;
			if ($quantity > 0 && $quantity < 100) {
				if (Yii::$app->cart->add($product->id, $quantity)) {
					$result['type'] = 'success';
					$result['message'] = 'Успешное добавление в корзину: <b>' . $product->title . '</b>';
				} else {
					$result['message'] = 'Неудачная попытка добавления товара в корзину';
				}
			} else {
				$result['message'] = 'Недопустимое количество товара. Можно от 1 до 99.';
			}
		} else {
			$result['message'] = 'Товар не найден';
		}

		if (Yii::$app->request->isAjax) {
			$response = new Response();
			$response->data = $result;
			$response->format = Response::FORMAT_JSON;
			return Yii::$app->end(0, $response);
		} else {
			Yii::$app->session->addFlash($result['type'], $result['message']);
			return $this->redirect(Yii::$app->request->referrer);
		}
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
