<?php

namespace backend\controllers;

use Yii;
use backend\models\Order;
use backend\models\OrderSearch;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
}
