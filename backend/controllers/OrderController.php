<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderSearch;
use common\classes\document\OrderManager;
use common\classes\OrderStatusWorkflow;
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
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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
	 * TODO: Пересмотреть контроллер. Реализован в качестве тестирования кассы
	 *
	 * @param $id
	 * @return \yii\web\Response
	 */
	public function actionCashbox($id)
	{
		$model = $this->findModel($id);
		$cashbox = Yii::$app->cashbox;

		$orderProducts = $model->orderProducts;
		$user = $model->user;
		if ($orderProducts and $user) {

			$cashbox->setPhoneOrEmail($user->email);

			foreach ($orderProducts as $orderProduct) {

				$product = $orderProduct->product;
				if ($product) {
					$cashbox->setProduct($orderProduct->price, $orderProduct->quantity, $product->title);
				}
			}
		}

		$result = $cashbox->execute();
		if ($result === true) {
			Yii::$app->session->setFlash('success', "Заказ успешно отправлен в кассу");
		} else {
			Yii::$app->session->setFlash('error', "Ошибка при отправке в кассу. Код ошибки: $result");
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
}
