<?php

namespace backend\controllers;

use backend\models\CatalogProductSearch;
use backend\models\ManualProductSearch;
use common\models\CatalogProduct;
use common\models\ManualCategory;
use common\models\ManualProduct;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ManualProductController implements the CRUD actions for ManualProduct model.
 */
class ManualProductController extends Controller
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
     * Lists all ManualProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManualProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ManualProduct model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'category' => $model->manualCategory,
        ]);
    }

	/**
	 * @param int $categoryId
	 *
	 * @return string|\yii\web\Response
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionCreate(int $categoryId)
    {
    	$category = $this->findCategoryModel($categoryId);

        $model = new ManualProduct();
        $model->manual_category_id = $category->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveRelated();
            return $this->redirect(['manual-category/view', 'id' => $model->manual_category_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    /**
     * Updates an existing ManualProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['manual-category/view', 'id' => $model->manual_category_id]);
        }

        return $this->render('update', [
            'model' => $model,
	        'category' => $model->manualCategory,
        ]);
    }

	/**
	 * @param $id
	 *
	 * @return \yii\web\Response
	 *
	 * @throws NotFoundHttpException
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	$model->delete();

        return $this->redirect(['/manual-category/view', 'id' => $model->manual_category_id]);
    }

    /**
     * Finds the ManualProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ManualProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ManualProduct::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	/**
	 * @param $id
	 *
	 * @return ManualCategory
	 *
	 * @throws NotFoundHttpException
	 */
    protected function findCategoryModel($id)
    {
        if (($model = ManualCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	/**
	 * @param $id
	 *
	 * @return CatalogProduct
	 *
	 * @throws NotFoundHttpException
	 */
    protected function findCatalogProductModel($id)
    {
        if (($model = CatalogProduct::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	/**
	 * @return bool
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionSavePositions()
    {
    	$request = Yii::$app->request;

    	$id = $request->post('id');
    	$positions = $request->post('positions');

	    $model = $this->findModel($id);

	    $basePosition = array_shift($positions);
	    if ($basePosition) {
	    	$model->left = $basePosition['left'] ?? 0;
	    	$model->top = $basePosition['top'] ?? 0;
	    	$model->width = $basePosition['width'] ?? 0;
	    	$model->height = $basePosition['height'] ?? 0;
	    }

	    $model->positions = $positions ? json_encode($positions) : '[]';

	    return (int)$model->save(false);
    }

	/**
	 * @param int $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionCatalogProduct(int $id)
    {
	    $manualProduct = $this->findModel($id);

	    $dataProvider = new ActiveDataProvider([
		    'query' => $manualProduct->getCatalogProducts(),
	    ]);

	    $productSearch = new CatalogProductSearch();
	    $productSearch->excludedIds = $dataProvider->getKeys();
	    $productProvider = $productSearch->search(Yii::$app->request->queryParams);

	    return $this->render('catalog-product', [
		    'manualProduct' => $manualProduct,
		    'dataProvider' => $dataProvider,
		    'manualCategory' => $manualProduct->manualCategory,
		    'productSearch' => $productSearch,
		    'productProvider' => $productProvider,
	    ]);
    }

	/**
	 * @param int $id
	 * @param int $catalogProductId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionCatalogProductApply(int $id, int $catalogProductId)
	{
		$manualProduct = $this->findModel($id);
		$catalogProduct = $this->findCatalogProductModel($catalogProductId);

		$manualProduct->link('catalogProducts', $catalogProduct);
        $manualProduct->saveRelated($catalogProduct);

		return $this->redirect(['catalog-product', 'id' => $id]);
	}

	/**
	 * @param int $id
	 * @param int $catalogProductId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionCatalogProductRevoke(int $id, int $catalogProductId)
	{
		$manualProduct = $this->findModel($id);
		$catalogProduct = $this->findCatalogProductModel($catalogProductId);

		$manualProduct->unlink('catalogProducts', $catalogProduct);
		$manualProduct->removeRelated($catalogProduct);

        return $this->redirect(['catalog-product', 'id' => $id]);
	}
}
