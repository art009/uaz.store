<?php

namespace app\modules\pms\controllers;

use backend\modules\pms\models\ShopImportForm;
use Yii;
use app\modules\pms\models\ShopItem;
use app\modules\pms\models\ShopItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopItemController implements the CRUD actions for ShopItem model.
 */
class ShopItemController extends Controller
{
    /**
     * Lists all ShopItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing ShopItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the ShopItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая позиция не найдена.');
        }
    }

	/**
	 * Импорт товаров
	 *
	 * @return string
	 */
	public function actionImport()
	{
		$model = new ShopImportForm();
		if ($model->load(Yii::$app->request->post()) && $model->import()) {
			Yii::$app->session->setFlash('success', 'Добавлено позиций: ' . $model->getCounterValue($model::COUNTER_INSERT));
			Yii::$app->session->setFlash('info', 'Обновлено позиций: ' . $model->getCounterValue($model::COUNTER_UPDATE));
			Yii::$app->session->setFlash('warning', 'Скрыто позиций: ' . $model->getCounterValue($model::COUNTER_DELETE));

			return $this->refresh();
		}

		return $this->render('import', [
			'model' => $model,
		]);
	}
}
