<?php

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use backend\models\MailQueue;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * MailQueueController implements the CRUD actions for MailQueue model.
 */
class MailQueueController extends Controller
{
    /**
     * Lists all MailQueue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MailQueue::find()->orderBy('status ASC, id DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing MailQueue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MailQueue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MailQueue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MailQueue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Отправка писем вручную
     *
     * @return \yii\web\Response
     */
    public function actionSend()
    {
        $count = MailQueue::send();
        Yii::$app->session->setFlash('success', 'Отправлено писем: ' . $count);

        return $this->redirect(['index']);
    }
}
