<?php

namespace frontend\controllers;

use common\models\Order;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;

/**
 * Контроллер оплат
 */
class PaymentController extends Controller
{
	/**
	 * @param $action
	 *
	 * @return bool
	 *
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function beforeAction($action)
	{
		if ($action->id == 'notice') {
			$this->enableCsrfValidation = false;
			$this->layout = false;
		}

		return parent::beforeAction($action);
	}

	/**
	 * Успешная оплата заказа
	 *
	 * @return string
	 */
	public function actionSuccess()
	{
		Yii::$app->session->setFlash('success', 'Вы успешно оплатили заказ! Следите за его исполнением в личном кабинете.');

		return $this->redirect('/order');
	}
	/**
	 * Неуспешная оплата заказа
	 *
	 * @return string
	 */
	public function actionFail()
	{
		Yii::$app->session->setFlash('danger', 'Неуспешная оплата заказа! Если возникли проблемы, свяжитесь с администрацией.');

		return $this->redirect('/order');
	}

	/**
	 * Получение уведомления о платеже
	 *
	 * @return string
	 *
	 * @throws InvalidConfigException
	 */
	public function actionNotice()
	{
		$data = Yii::$app->getRequest()->getBodyParams();
		if (!is_array($data) || empty($data)) {
			return $this->renderContent('Error! Incorrect data');
		}

		$secret = Yii::$app->params['payment.secretSeed'] ?? '';
		$id = $data['id'] ?? '';
		$sum = $data['sum'] ?? '';
		$clientId = $data['clientid'] ?? '';
		$orderId = $data['orderid'] ?? '';
		$key = $data['key'] ?? '';

		if ($key != md5($id . sprintf ("%.2lf", $sum). $clientId . $orderId . $secret)) {
			return $this->renderContent('Error! Hash mismatch');
		}

		$order = Order::findOne(['id' => $orderId, 'user_id' => $clientId]);
		if (!$order) {
			return $this->renderContent('Error! Order not found');
        }

        if (in_array($order->status, [Order::STATUS_PAYMENT_WAITING, Order::STATUS_PAYMENT_PROCESS])) {
        	$order->updateAttributes([
        		'status' => Order::STATUS_PAYMENT_DONE,
		        'changed_at' => date('Y-m-d H:i:s'),
		        'updated_at' => date('Y-m-d H:i:s'),
		        'payment_id' => $id,
	        ]);
        }

		return $this->renderContent('OK ' . md5($id . $secret));
	}
}
