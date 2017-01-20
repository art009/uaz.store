<?php

namespace backend\controllers;

use Yii;
use common\models\CatalogCategory;
use backend\models\CatalogCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogCategoryController implements the CRUD actions for CatalogCategory model.
 */
class CatalogCategoryController extends Controller
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
     * Список категорий
     *
     * @param int|null $id
     *
     * @return string
     */
    public function actionIndex($id = null)
    {
        $searchModel = new CatalogCategorySearch();
        $searchModel->parent_id = $id;
        $parentModel = $id ? $this->findModel($id) : null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'parentModel' => $parentModel,
        ]);
    }

    /**
     * Displays a single CatalogCategory model.
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
     * Создание категории
     *
     * @param int|null $id
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate($id = null)
    {
        $model = new CatalogCategory();
        $model->parent_id = $id;
        $parentModel = $id ? $this->findModel($id) : null;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->parent_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'parentModel' => $parentModel,
            ]);
        }
    }

    /**
     * Updates an existing CatalogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $parentModel = $model->parent_id ? $this->findModel($model->parent_id) : null;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->parent_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'parentModel' => $parentModel,
            ]);
        }
    }

    /**
     * Deletes an existing CatalogCategory model.
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
     * Finds the CatalogCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
