<?php

namespace backend\controllers;

use backend\models\CatalogProductSearch;
use backend\models\Order;
use backend\models\OrderSearch;
use common\classes\document\OrderManager;
use common\classes\OrderStatusWorkflow;
use common\models\CatalogProduct;
use common\models\OrderProduct;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
	private const RETURN_PASSWORD_HASH = '56004f688056b0a55d8c898f40371fdb31939f79';

	/**
	 * @param int|null $userId
	 *
	 * @return string
	 */
    public function actionIndex($userId = null)
    {
        $searchModel = new OrderSearch();
        $searchModel->user_id = $userId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	/**
	 * Просмотр заказа
	 *
	 * @param $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionView($id)
    {
	    $order = $this->findModel($id);

	    $productSearch = new CatalogProductSearch();
	    $productSearch->excludedIds = $order->getOrderProducts()->select(['product_id'])->column();
	    $productProvider = $productSearch->search(Yii::$app->request->queryParams, 10);

        return $this->render('view', [
            'order' => $order,
	        'productSearch' => $productSearch,
	        'productProvider' => $productProvider,
        ]);
    }

	/**
	 * Смена статуса
	 *
	 * @param int $id
	 * @param int $status
	 *
	 * @return Response
	 *
	 * @throws BadRequestHttpException
	 * @throws NotFoundHttpException
	 * @throws \yii\db\Exception
	 */
    public function actionChangeStatus(int $id, int $status): Response
    {
    	$model = $this->findModel($id);

    	if (!in_array($status, OrderStatusWorkflow::statusList($model->status))) {
		    throw new BadRequestHttpException('Попытка перевода в недопустимый статус.');
	    }

	    if (!$model->getWorkflow()->toStatus($status)) {
		    throw new BadRequestHttpException('Неудачная попытка перевода в статус.');
	    }

    	return $this->redirect(['view', 'id' => $id]);
    }

	/**
	 * @param integer $userId
	 *
	 * @return \yii\web\Response
	 *
	 * @throws BadRequestHttpException
	 */
    public function actionCreate($userId)
    {
        $order = Order::create($userId);
		if ($order !== null) {
			return $this->redirect(['view', 'id' => $order->id]);
		} else {
			throw new BadRequestHttpException('Не удалось создать заказ или найти уже созданный.');
		}
    }

	/**
	 * @param $id
	 *
	 * @return Response
	 *
	 * @throws NotFoundHttpException
	 * @throws \yii\base\Exception
	 */
	public function actionCashBox($id)
	{
		$model = $this->findModel($id);
		$cashbox = Yii::$app->cashbox;

		$orderProducts = $model->orderProducts;
		$user = $model->user;
		if ($orderProducts and $user) {

			$cashbox->setPhoneOrEmail($user->email);
			$cashbox->setDelivery($model);
			$cashbox->setSending($model);

			foreach ($orderProducts as $orderProduct) {

				$product = $orderProduct->product;
				if ($product) {
					$cashbox->setProduct($orderProduct->price, $orderProduct->quantity, $product->title);
				}
			}
		}

		$model->updateAttributes([
			'cash_box_sent_error' => null,
		]);

		$result = $cashbox->execute();
		if ($result === true) {
			$model->updateAttributes([
				'cash_box_sent_at' => date('Y-m-d H:i:s'),
			]);
			Yii::$app->session->setFlash('success', "Заказ успешно отправлен в кассу");
		} else {
			$model->updateAttributes([
				'cash_box_sent_error' => $result,
			]);
			Yii::$app->session->setFlash('error', "Ошибка при отправке в кассу. Код ошибки: $result");
		}

		return $this->redirect(['/order']);
    }

	/**
	 * @param int $id
	 *
	 * @return string|Response
	 *
	 * @throws NotFoundHttpException
	 * @throws \yii\base\Exception
	 */
	public function actionCashBoxReturn(int $id)
	{
		$model = $this->findModel($id);

		$password = Yii::$app->request->getBodyParam('password');
		$hash = sha1($password);
		if ($hash != self::RETURN_PASSWORD_HASH) {
			Yii::$app->session->setFlash('error', 'Некорректный пароль подтверждения возврата');
			return $this->render('return-password', [
				'order' => $model,
			]);
		}

		$cashBox = Yii::$app->cashbox;
		$cashBox->applyReturnDocumentType();


		$orderProducts = $model->orderProducts;
		$user = $model->user;
		if ($orderProducts and $user) {

			$cashBox->setPhoneOrEmail($user->email);

			foreach ($orderProducts as $orderProduct) {

				$product = $orderProduct->product;
				if ($product) {
					$cashBox->setProduct($orderProduct->price, $orderProduct->quantity, $product->title);
				}
			}
		}

		$model->updateAttributes([
			'cash_box_return_error' => null,
		]);

		$result = $cashBox->execute();
		if ($result === true) {
			$model->updateAttributes([
				'cash_box_return_at' => date('Y-m-d H:i:s'),
			]);
			Yii::$app->session->setFlash('success', "Возврат успешно оформлен по заказу");
		} else {
			$model->updateAttributes([
				'cash_box_return_error' => $result,
			]);
			Yii::$app->session->setFlash('error', "Ошибка при оформлении возврата. Код ошибки: $result");
		}

		return $this->redirect(['/order']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемый заказ не найден.');
        }
    }

	/**
	 * @param int $id
	 *
	 * @param string $type TODO В будущем убрать дефолтное значение
	 *
	 * @return Response
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionDocument(int $id, string $type = OrderManager::TYPE_INDIVIDUAL_USER_INVOICE)
	{
		$order = $this->findModel($id);
		$manager = new OrderManager($order);
		$errors = $manager->checkDocument($type);
		if (empty($errors)) {
			$filePath = $manager->getDocumentPath($type);
			if ($filePath) {
				return Yii::$app->response->sendFile($filePath, $manager->getDocumentLabel($type))->send();
			}
		} else {
			foreach ($errors as $type => $messages) {
				foreach ($messages as $message) {
					Yii::$app->session->setFlash($type, '<b>Проблема генерации документа для заказа № ' . $id . '</b><br/> ' . $message);
				}
			}
		}

		return $this->redirect(['/order']);
	}

	/**
	 * @param int $orderId
	 * @param int $productId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDeleteProduct(int $orderId, int $productId)
	{
		$order = $this->findModel($orderId);
		$orderProducts = $order->orderProducts;
		if (count($orderProducts) <= 1) {
			Yii::$app->session->setFlash('warning', "Нельзя удалить последний товар");
		} else {
			foreach ($orderProducts as $orderProduct) {
				if ($orderProduct->product_id == $productId) {
					if ($orderProduct->delete()) {
						$order->updateSum(true);
					} else {
						Yii::$app->session->setFlash('danger', "Не удается удалить товар");
					}
				}
			}
		}

		return $this->actionView($orderId);
	}

	/**
	 * @param int $orderId
	 * @param int $productId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionIncProduct(int $orderId, int $productId)
	{
		$order = $this->findModel($orderId);
		$orderProducts = $order->orderProducts;

		foreach ($orderProducts as $orderProduct) {
			if ($orderProduct->product_id == $productId) {
				if (!$orderProduct->updateQuantity($orderProduct->getQuantity() + 1)) {
					Yii::$app->session->setFlash('danger', "Не удается изменить количество товара");
				}
			}
		}

		return $this->actionView($orderId);
	}

	/**
	 * @param int $orderId
	 * @param int $productId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDecProduct(int $orderId, int $productId)
	{
		$order = $this->findModel($orderId);
		$orderProducts = $order->orderProducts;

		foreach ($orderProducts as $orderProduct) {
			if ($orderProduct->product_id == $productId) {
				$quantity = $orderProduct->getQuantity();
				if ($quantity <= 1 || !$orderProduct->updateQuantity($quantity - 1)) {
					Yii::$app->session->setFlash('danger', "Не удается изменить количество товара");
				}
			}
		}

		return $this->actionView($orderId);
	}

	/**
	 * @param int $orderId
	 * @param int $productId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionAddProduct(int $orderId, int $productId)
	{
		$order = $this->findModel($orderId);
		$product = CatalogProduct::findOne($productId);

		if ($product) {
			$orderProduct = new OrderProduct();
			$orderProduct->product_id = $product->id;
			$orderProduct->order_id = $order->id;
			$orderProduct->quantity = 1;
			$orderProduct->price = $product->price;
			if (!$orderProduct->save()) {
				Yii::$app->session->setFlash('danger', "Не удается добавить товар");
			}
		} else {
			Yii::$app->session->setFlash('danger', "Товар не найден.");
		}

		return $this->actionView($orderId);
	}
}
