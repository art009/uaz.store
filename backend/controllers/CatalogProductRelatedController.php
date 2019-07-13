<?php

namespace backend\controllers;

use common\models\CatalogProduct;
use Yii;
use common\models\CatalogProductRelated;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogProductRelatedController implements the CRUD actions for CatalogProductRelated model.
 */
class CatalogProductRelatedController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all CatalogProductRelated models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CatalogProductRelated::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogProductRelated model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CatalogProductRelated model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatalogProductRelated();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $result = $model->save();
                $model->saveRelated();
                $idToGo = CatalogProductRelated::findOne([
                    'product_id' => $model->product_id,
                    'related_product_id' => $model->related_product_id
                ])->id;
            } catch (\Exception $e) {
                $result = false;
            }
            if ($result) {
                return $this->redirect(['view', 'id' => $idToGo]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CatalogProductRelated model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $result = $model->save();
                $model->saveRelated();
                $idToGo = CatalogProductRelated::findOne([
                    'product_id' => $model->product_id,
                    'related_product_id' => $model->related_product_id
                ])->id;
            } catch (\Exception $e) {
                $result = true;
            }
            if ($result) {
                return $this->redirect(['view', 'id' => $idToGo]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CatalogProductRelated model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogProductRelated model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogProductRelated the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogProductRelated::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFilterCatalogProduct($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_null($q)) {
            $query = "'%" . $q . "%'";
            $sql = "SELECT * FROM catalog_product WHERE title LIKE $query";
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sql);
            $result = $command->queryAll();
            $out = [];
            foreach ($result as $req) {
                $value = $req['title'];
                $item = ['id' => $req['id'], 'text' => $value];
                $out['results'][] = $item;
            }
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => CatalogProduct::findOne($id)->title];
        }
        return $out;
    }
}
