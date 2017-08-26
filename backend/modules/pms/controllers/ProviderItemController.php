<?php

namespace app\modules\pms\controllers;

use app\modules\pms\models\Provider;
use backend\modules\pms\components\ProviderItemAcceptCache;
use backend\modules\pms\models\ProviderItemAcceptForm;
use backend\modules\pms\models\ProviderItemImportForm;
use Yii;
use app\modules\pms\models\ProviderItem;
use app\modules\pms\models\ProviderItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProviderItemController implements the CRUD actions for ProviderItem model.
 */
class ProviderItemController extends Controller
{
	/**
	 * Список товаров поставщика
	 *
	 * @param int $providerId
	 *
	 * @return string
	 */
    public function actionIndex(int $providerId)
    {
    	$provider = $this->findProviderModel($providerId);

        $searchModel = new ProviderItemSearch();
        $searchModel->provider_id = $provider->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'provider' => $provider,
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
     * Updates an existing ProviderItem model.
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
            throw new NotFoundHttpException('Товар не найден.');
        }
    }

    /**
     * Finds the ProviderItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Provider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProviderModel(int $id)
    {
        if (($model = Provider::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Поставщик не найден.');
        }
    }

	/**
	 * Подтверждение обновления товаров.
	 * @param int $providerId
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionAccept(int $providerId)
	{
		$provider = $this->findProviderModel($providerId);

		$model = new ProviderItemAcceptForm(['providerId' => $provider->id]);

		if ($model->load(Yii::$app->request->post()) && $model->process()) {
			return $this->redirect(['index', 'providerId' => $provider->id]);
		};
		$dataProvider = $model->getDataProvider();

		return $this->render('accept', [
			'providerId' => $providerId,
			'dataProvider' => $dataProvider,
			'model' => $model,
			'provider' => $provider,
		]);
	}
	/**
	 * Импорт товаров
	 * @param int $providerId
	 * @return string|\yii\web\Response
	 */
	public function actionImport(int $providerId)
	{
		$provider = $this->findProviderModel($providerId);
		$cache = $this->getAcceptCache($provider->id);
		if ($cache->exists()) {
			return $this->actionAccept($providerId);
		} else {
			$model = new ProviderItemImportForm(['provider_id' => $provider->id]);
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
				'provider' => $provider,
			]);
		}
	}

	/**
	 * Чистит кеш ожидающих подтверждения товаров.
	 *
	 * @param int $providerId
	 *
	 * @return mixed
	 */
	public function actionCancel(int $providerId)
	{
		$cache = $this->getAcceptCache($providerId);
		$cache->clear();

		return $this->redirect(['index', 'providerId' => $providerId]);
	}

	/**
	 * @param int $providerId
	 *
	 * @return ProviderItemAcceptCache
	 */
	protected function getAcceptCache(int $providerId)
	{
		return new ProviderItemAcceptCache($providerId);
	}
}
