<?php

namespace backend\controllers;

use backend\models\Manual;
use backend\models\ManualCategorySearch;
use backend\models\ManualProductSearch;
use common\models\ManualCategory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ManualCategoryController implements the CRUD actions for ManualCategory model.
 */
class ManualCategoryController extends Controller
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
	 * @param int $manualId
	 * @param int|null $categoryId
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionIndex(int $manualId, int $categoryId = null)
    {
    	$manual = $this->findManualModel($manualId);
    	$category =  $categoryId ? $this->findModel($categoryId) : null;

        $searchModel = new ManualCategorySearch();
	    $searchModel->manual_id = $manual->id;
	    $searchModel->parent_id = $categoryId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'manual' => $manual,
            'category' => $category,
        ]);
    }

    /**
     * Displays a single ManualCategory model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);

	    $searchModel = new ManualProductSearch();
	    $searchModel->manual_category_id = $id;

	    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	    $dataProvider->setPagination(false);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

	/**
	 * @param int $manualId
	 * @param int|null $categoryId
	 *
	 * @return string|\yii\web\Response
	 */
    public function actionCreate(int $manualId, int $categoryId = null)
    {
        $model = new ManualCategory();
        $model->manual_id = $manualId;
        $model->parent_id = $categoryId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ManualCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

	/**
	 * @param $id
	 * @return \yii\web\Response
	 *
	 * @throws NotFoundHttpException
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	/**
	 * @param $id
	 *
	 * @return ManualCategory
	 *
	 * @throws NotFoundHttpException
	 */
    protected function findModel($id)
    {
        if (($model = ManualCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	/**
	 * @param $id
	 *
	 * @return Manual
	 *
	 * @throws NotFoundHttpException
	 */
    protected function findManualModel($id)
    {
        if (($model = Manual::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
