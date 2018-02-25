<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\User;
use frontend\components\FrontAppComponentTrait;
use frontend\models\ConfirmOrderForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class OrderController
 *
 * @package frontend\controllers
 */
class OrderController extends Controller
{
	use FrontAppComponentTrait;

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['confirm'],
				'rules' => [
					[
						'actions' => ['confirm'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	/**
	 * Подтверждение заказа
	 *
	 * @return string
	 */
	public function actionConfirm()
	{
		$cart = $this->getCartComponent();
		if ($cart->getIsEmpty()) {
			return $this->redirect(['/cart']);
		}
		/** @var User $user */
		$user = $this->getUserComponent()->getIdentity();
		$order = Order::create($user->getId());
		if (is_null($order)) {
			$this->getSessionComponent()->addFlash('danger', 'Невозможно продолжить оформление заказа.');
			return $this->redirect($this->getRequestComponent()->referrer);
		}
		$confirmForm = new ConfirmOrderForm([
			'order' => $order,
			'user' => $user,
		]);

		if ($confirmForm->load($this->getRequestComponent()->post())) {
			if ($confirmForm->confirm() && $order->confirm()) {
				$this->getSessionComponent()->setFlash('success', 'Заказ успешно оформлен. Следите за его исполнением в личном кабинете.');
				return $this->redirect(['/user']);
			} else {
				$this->getSessionComponent()->setFlash('warning', 'Не удалось оформить заказ. Свяжитесь с администрацией.');
			}
		}

		return $this->render('confirm', [
			'order' => $order,
			'confirmForm' => $confirmForm,
		]);
	}

	/**
	 * @return Response
	 */
	public function actionReject()
	{
		Yii::$app->session->addFlash('warning', 'Отмена заказа пока невозможна.');
		return $this->redirect(Yii::$app->request->referrer);
	}
}
