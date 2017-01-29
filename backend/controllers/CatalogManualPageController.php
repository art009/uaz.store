<?php

namespace backend\controllers;

use backend\models\CatalogCategory;
use backend\models\CatalogManual;
use Yii;
use backend\models\CatalogManualPage;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogManualPageController implements the CRUD actions for CatalogManualPage model.
 */
class CatalogManualPageController extends Controller
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
     * Lists all CatalogManualPage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CatalogManualPage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogManualPage model.
     * @param integer $cid
     * @param integer $mid
     * @return mixed
     */
    public function actionView($cid, $mid)
    {
		$model = $this->findModel($cid, $mid);

		$manual = $this->findManualModel($model->manual_id);
		$category = $this->findCategoryModel($model->category_id);

		return $this->render('view', [
			'model' => $model,
			'manual' => $manual,
			'category' => $category,
        ]);
    }

	/**
	 * Creates a new CatalogManualPage model.
	 *
	 * @param integer $cid
	 * @param integer $mid
	 *
	 * @return string|\yii\web\Response
	 */
    public function actionCreate($cid, $mid)
    {
    	$category = $this->findCategoryModel((int)$cid);
    	$manual = $this->findManualModel((int)$mid);

    	$model = new CatalogManualPage();

        $model->category_id = $category->id;
        $model->manual_id = $manual->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/catalog-manual/view', 'id' => $model->manual_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'manual' => $manual,
                'category' => $category,
            ]);
        }
    }

    /**
     * Updates an existing CatalogManualPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $cid
	 * @param integer $mid
	 *
     * @return mixed
     */
    public function actionUpdate($cid, $mid)
    {
        $model = $this->findModel($cid, $mid);

        $manual = $this->findManualModel($model->manual_id);
		$category = $this->findCategoryModel($model->category_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['/catalog-manual/view', 'id' => $model->manual_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'manual' => $manual,
                'category' => $category,
            ]);
        }
    }

    /**
     * Deletes an existing CatalogManualPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $cid
	 * @param integer $mid
	 *
     * @return mixed
     */
    public function actionDelete($cid, $mid)
    {
        $this->findModel($cid, $mid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogManualPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $categoryId
     * @param integer $manualId
     * @return CatalogManualPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($categoryId, $manualId)
    {
    	/* @var $model CatalogManualPage */
		$model = CatalogManualPage::find()
			->where([
				'category_id' => $categoryId,
				'manual_id' => $manualId,
			])
			->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница справочника не найдена.');
        }
    }

    /**
     * Finds the CatalogManualPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogManual the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findManualModel($id)
    {
        if (($model = CatalogManual::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Справочник не найден.');
        }
    }

    /**
     * Finds the CatalogManualPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCategoryModel($id)
    {
        if (($model = CatalogCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Категория не найдена.');
        }
    }
}
