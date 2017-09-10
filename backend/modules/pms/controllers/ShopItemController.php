<?php

namespace app\modules\pms\controllers;

use app\modules\pms\models\Provider;
use app\modules\pms\models\ProviderItem;
use app\modules\pms\models\ProviderShopItem;
use backend\modules\pms\components\PriceExporter;
use backend\modules\pms\components\SimilarPositionResolver;
use backend\modules\pms\models\ShopImportForm;
use common\components\AppHelper;
use Yii;
use app\modules\pms\models\ShopItem;
use app\modules\pms\models\ShopItemSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

	/**
	 * Страница связей
	 *
	 * @param int $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionBind(int $id)
	{
		$model = $this->findModel($id);

		$providerId = (int)Yii::$app->request->post('providerId', Provider::find()->select(['id'])->scalar());
		/* @var $provider Provider */
		$provider = Provider::findOne($providerId);
		if ($provider === null) {
			throw new NotFoundHttpException('Не найден поставщик.');
		}


		$providerItemQuery = ProviderItem::find();
		$wordSearchQuery = Yii::$app->request->post('wordSearch');
		$searchQuery = null;
		if ($wordSearchQuery) {
			$providerItemQuery->andFilterWhere(['like', 'title', $wordSearchQuery]);
			$providerItemQuery->orFilterWhere(['like', 'vendor_code', $wordSearchQuery]);
		} else {
			$searchQuery = Yii::$app->request->post('search');
			if (!$searchQuery) {
				$searchQuery = $model->title;
			}
			$resolver = new SimilarPositionResolver($provider->id, $searchQuery, $searchQuery != $model->title ? $model->vendor_code : null);
			$providerItemQuery->where(['id' => $resolver->getIds()]);
		}

		$dataProvider = new ActiveDataProvider([
			'query' => $providerItemQuery->limit(100),
			'sort' => false,
			'pagination' => false,
		]);

		$linkDataProvider = new ActiveDataProvider([
			'query' => $model->getProviderItems(),
			'sort' => false,
			'pagination' => false,
		]);

		$providerList = ArrayHelper::map(Provider::find()->all(), 'id', 'name');

		return $this->render('bind', [
			'model' => $model,
			'provider' => $provider,
			'dataProvider' => $dataProvider,
			'searchQuery' => $searchQuery,
			'wordSearchQuery' => $wordSearchQuery,
			'linkDataProvider' => $linkDataProvider,
			'providerList' => $providerList,
		]);
	}

	/**
	 * Для окна с позициями поставщика
	 *
	 * @param int $providerId
	 *
	 * @return string
	 */
	public function actionList(int $providerId)
	{
		$provider = Provider::findOne($providerId);

		$dataProvider = new ActiveDataProvider([
			'query' => ProviderItem::find()->where(['provider_id' => $providerId])->orderBy('code'),//->limit(100),
			'sort' => false,
			'pagination' => false,
		]);

		return $this->renderPartial('list', [
			'provider' => $provider,
			'dataProvider' => $dataProvider,
		]);

	}


	/**
	 * Finds the ProviderItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ProviderItem the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findProviderItemModel($id)
	{
		if (($model = ProviderItem::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('Запрашиваемая позиция не найдена.');
		}
	}
	/**
	 * Связывает товар магазина и товар поставщика
	 *
	 * @param int $id
	 * @param int $shopItemId
	 *
	 * @return bool
	 */
	public function actionLink(int $id, int $shopItemId)
	{
		$shopItem = $this->findModel($shopItemId);

		$providerItem = $this->findProviderItemModel($id);

		$shopItem->link('providerItems', $providerItem);

		$exporter = new PriceExporter(Yii::$app->db);
		$exporter->calculate($shopItem->id);

		return true;
	}

	/**
	 * Отвязывает товар магазина и товар поставщика
	 *
	 * @param int $id
	 * @param int $shopItemId
	 *
	 * @return bool
	 */
	public function actionUnlink(int $id, int $shopItemId)
	{
		$shopItem = $this->findModel($shopItemId);

		$providerItem = $this->findProviderItemModel($id);

		$shopItem->unlink('providerItems', $providerItem, true);

		$exporter = new PriceExporter(Yii::$app->db);
		$exporter->calculate($shopItem->id);

		return true;
	}

	/**
	 * Пересчет
	 */
	public function actionCalculate()
	{
		Yii::$app->cache->delete('shop-item-search-status');

		$exporter = new PriceExporter(Yii::$app->db);

		Yii::$app->session->setFlash('info', 'Пересчитано позиций: ' . $exporter->calculate());

		return $this->redirect('index');
	}

	/**
	 * Пересчет и выгрузка цен
	 */
	public function actionExport()
	{
		Yii::$app->cache->delete('shop-item-search-status');

		$exporter = new PriceExporter(Yii::$app->db);

		Yii::$app->session->setFlash('info', 'Обновлено цен: ' . $exporter->export(true));

		return $this->redirect('index');
	}

	/**
	 * @param int $id
	 *
	 * @return \yii\web\Response
	 */
	public function actionIgnore(int $id)
	{
		$model = $this->findModel($id);
		$ignored = !$model->ignored;
		$model->updateAttributes([
			'ignored' => $ignored,
		]);
		if ($ignored == AppHelper::YES) {
			Yii::$app->session->setFlash('warning', 'Позиция магазина добавлена в игнор.');
		} else {
			Yii::$app->session->setFlash('success', 'Позиция магазина убрана из игнора.');
		}

		return $this->redirect(['bind', 'id' => $id]);
	}

	/**
	 * @param int $id
	 *
	 * @return \yii\web\Response
	 */
	public function actionNotFound(int $id)
	{
		$model = $this->findModel($id);
		$notFound = !$model->status;
		$model->updateAttributes([
			'status' => $notFound,
		]);
		if ($notFound == AppHelper::YES) {
			Yii::$app->session->setFlash('warning', 'Позиция магазина помечена ненайденной у поставщика');
		} else {
			Yii::$app->session->setFlash('success', 'У позиции магазина убрана отметка ненайденной.');
		}

		return $this->redirect(['bind', 'id' => $id]);
	}

	/**
	 * @param int $providerItemId
	 * @param int $shopItemId
	 * @param int $quantity
	 *
	 * @return int
	 */
	public function actionQuantity(int $providerItemId, int $shopItemId, int $quantity)
	{
		$result = ProviderShopItem::updateAll(['quantity' => $quantity], [
			'shop_item_id' => $shopItemId,
			'provider_item_id' => $providerItemId,
		]);

		$exporter = new PriceExporter(Yii::$app->db);
		$exporter->calculate($shopItemId);

		return $result;
	}

	/**
	 * @param int $id
	 *
	 * @return string
	 */
	public function actionCompare(int $id)
	{
		$model = $this->findModel($id);

		return $this->renderPartial('compare', [
			'model' => $model,
		]);
	}
}
