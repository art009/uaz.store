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
	 * @param array $data
	 *
	 * @return Response
	 */
	protected function renderJson($data)
	{
		$response = new Response();
		$response->data = $data;
		$response->format = Response::FORMAT_JSON;
		return $response;
	}

	/**
	 * Ответ корзины
	 *
	 * @param array $data
	 *
	 * @return Response
	 */
	protected function cartResponse($data)
	{
		if (Yii::$app->request->isAjax) {
			return $this->renderJson($data);
		} else {
			if (array_key_exists('type', $data) && array_key_exists('message', $data)) {
				Yii::$app->session->addFlash($data['type'], $data['message']);
			}
			return $this->redirect(Yii::$app->request->referrer);
		}
	}

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
		$cart = Yii::$app->cart;

		$result = [
			'type' => 'danger',
			'message' => 'Неизвестная ошибка',
			'count' => $cart->getQuantity(),
		];

		$product = CatalogProduct::findOne((int)$productId);
		if ($product) {
			$quantity = (int)$quantity;
			if ($quantity > 0 && $quantity < 100) {
				if ($cart->add($product->id, $quantity)) {
					$result['type'] = 'success';
					$result['message'] = 'Успешное добавление в корзину: <b>' . $product->title . '</b>';
					$result['count'] += $quantity;
				} else {
					$result['message'] = 'Неудачная попытка добавления товара в корзину';
				}
			} else {
				$result['message'] = 'Недопустимое количество товара. Можно от 1 до 99.';
			}
		} else {
			$result['message'] = 'Товар не найден';
		}

		return $this->cartResponse($result);
	}

	/**
	 * Прибавление товара
	 *
	 * @param int $productId
	 *
	 * @return \yii\web\Response
	 */
	public function actionInc($productId)
	{
		$cart = Yii::$app->cart;

		$result = [
			'type' => 'danger',
			'message' => 'Неизвестная ошибка',
			'count' => $cart->getQuantity(),
		];

		if ($cart->inc((int)$productId)) {
			$result['type'] = 'success';
			$result['message'] = 'Успешное изменение количества в корзину!';
			$cart->reload();
			$result['count'] = $cart->getQuantity();
			$result['total'] = number_format($cart->getSum(), 2, '.', ' ');
			$result['quantity'] = $cart->getQuantity($productId);
			$result['sum'] = $cart->getSum($productId);
		} else {
			$result['message'] = 'Неудачная попытка изменения количества товара в корзине';
		}

		return $this->cartResponse($result);
	}

	/**
	 * Убавление товара
	 *
	 * @param int $productId
	 *
	 * @return \yii\web\Response
	 */
	public function actionDec($productId)
	{
		$cart = Yii::$app->cart;

		$result = [
			'type' => 'danger',
			'message' => 'Неизвестная ошибка',
			'count' => $cart->getQuantity(),
		];

		if ($cart->dec((int)$productId)) {
			$result['type'] = 'success';
			$result['message'] = 'Успешное изменение количества в корзину!';
			$cart->reload();
			$result['count'] = $cart->getQuantity();
			$result['total'] = number_format($cart->getSum(), 2, '.', ' ');
			$result['quantity'] = $cart->getQuantity($productId);
			$result['sum'] = $cart->getSum($productId);
		} else {
			$result['message'] = 'Неудачная попытка изменения количества товара в корзине';
		}

		return $this->cartResponse($result);
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
