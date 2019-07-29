<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\User;
use common\models\UserOrder;
use frontend\components\FrontAppComponentTrait;
use frontend\models\ConfirmOrderForm;
use frontend\models\search\OrderSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
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
				'only' => ['index', 'view', 'confirm'],
				'rules' => [
					[
						'actions' => ['index', 'view', 'confirm'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

    /**
     * The user order list in user profile
     *
     * @return string|Response
     */
    public function actionIndex()
    {
        $user = $this->getUserComponent();
        if ($user === null) {
            return $this->goHome();
        }

        $searchModel = new OrderSearch();
        $notStatus = [$searchModel::STATUS_CART, $searchModel::STATUS_CART_CLEAR];
        $dataProvider = $searchModel->search($user->getId(), $this->getRequestComponent()->queryParams, $notStatus);
        $dataProvider->setPagination(['pageSize' => 3]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	/**
	 * @param $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 * @throws ForbiddenHttpException
	 */
    public function actionView($id)
    {
    	$order = Order::findOne($id);
    	if (!$order) {
    		throw new NotFoundHttpException('Заказ не найден.');
	    }
	    /** @var User $user */
	    $user = $this->getUserComponent()->getIdentity();
	    if (!$user || $order->original_user_id != $user->id) {
	    	throw new ForbiddenHttpException('Заказ недоступен.');
	    }

        $user = UserOrder::findOne($order->user_id);
        return $this->render('view', [
        	'order' => $order,
        	'user' => $user,
        ]);
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
			    Yii::$app->cart->clear();
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

    /**
     * @param $id
     * @return Response
     */
    public function actionDocument($id)
    {
        $model = new IndividualOrder($id, Yii::$app->user->getId());

        if ($model->validate()) {
            $filePath = $model->getFile();
            if ($filePath) {
                return Yii::$app->response->sendFile($filePath, $model->getAttachmentName())->send();
            }
        }

        $this->getSessionComponent()->setFlash('error', 'Необходимо заполнить профиль.');
        return $this->redirect(['/user']);
	}
}
