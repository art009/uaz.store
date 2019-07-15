<?php

namespace backend\controllers;

use common\models\CatalogProduct;
use Yii;
use common\models\CatalogProductSimilar;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogProductSimilarController implements the CRUD actions for CatalogProductSimilar model.
 */
class CatalogProductSimilarController extends Controller
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
     * Lists all CatalogProductSimilar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CatalogProductSimilar::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogProductSimilar model.
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
     * Creates a new CatalogProductSimilar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatalogProductSimilar();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $result =$model->save();
                $model->saveSimilar();
                $idToGo = CatalogProductSimilar::findOne(['product_id' => $model->product_id, 'similar_product_id' => $model->similar_product_id])->id;
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
     * Updates an existing CatalogProductSimilar model.
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
                $model->saveSimilar();
                $idToGo = CatalogProductSimilar::findOne(['product_id' => $model->product_id, 'similar_product_id' => $model->similar_product_id])->id;
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
     * Deletes an existing CatalogProductSimilar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $modelToDelete = $this->findModel($id);
        CatalogProductSimilar::deleteAll(['similar_product_id' => $modelToDelete->similar_product_id, 'product_id' => $modelToDelete->id]);
        CatalogProductSimilar::deleteAll(['similar_product_id' => $modelToDelete->id, 'product_id' => $modelToDelete->similar_product_id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogProductSimilar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogProductSimilar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogProductSimilar::findOne($id)) !== null) {
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
