<?php

namespace app\modules\pms\controllers;

use backend\modules\pms\models\ProviderItemAcceptForm;
use backend\modules\pms\models\ProviderItemImportForm;
use Yii;
use app\modules\pms\models\ProviderItem;
use app\modules\pms\models\ProviderItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProviderItemController implements the CRUD actions for ProviderItem model.
 */
class ProviderItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProviderItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProviderItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProviderItem model.
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
     * Creates a new ProviderItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProviderItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProviderItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'provider_id' => $model->provider_id]);
        } else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing ProviderItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);

		$model->delete();

        return $this->redirect(["index?provider_id=$model->provider_id"]);
    }

    /**
     * Finds the ProviderItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProviderItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProviderItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	/**
	 * Подтверждение обновления товаров.
	 * @param int $providerId
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionAccept($providerId) {
		$model = new ProviderItemAcceptForm();

		if ($model->load(Yii::$app->request->post())) {
			$providerId = $model->process($providerId);
			if ($providerId > 0){
				return $this->redirect(["index?providerId=$providerId"]);
			} else {
				throw new NotFoundHttpException('Ошибка подтвержедния импорта товаров');
			}
		};
		$dataProvider = $model->getDataProvider($providerId);

		return $this->render('accept', [
			'providerId' => $providerId,
			'dataProvider' => $dataProvider,
			'model'=>$model
		]);
	}
	/**
	 * Импорт товаров
	 * @param int $providerId
	 * @return string|\yii\web\Response
	 */
	public function actionImport($providerId)
	{
		if (Yii::$app->cache->exists('accept' . $providerId)) {
			return $this->actionAccept($providerId);
		} else {
			$model = new ProviderItemImportForm();
			if ($model->load(Yii::$app->request->post()) && $model->import()) {
				Yii::$app->session->setFlash('success',
					'Добавлено позиций: ' . $model->getCounterValue($model::COUNTER_INSERT));
				Yii::$app->session->setFlash('info',
					'Обновлено позиций: ' . $model->getCounterValue($model::COUNTER_UPDATE));
				Yii::$app->session->setFlash('warning',
					'Скрыто позиций: ' . $model->getCounterValue($model::COUNTER_DELETE));

				return $this->refresh();
			}
			return $this->render('import', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Чистит кеш ожидающих подтверждения товаров.
	 * @param int $providerId
	 * @return mixed
	 */
	public function actionCancel($providerId)
	{
		$model = new ProviderItemAcceptForm();

		$model->clearCache($providerId);

		return $this->redirect(["index?providerId=$providerId"]);
	}
}
