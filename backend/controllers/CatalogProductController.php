<?php

namespace backend\controllers;

use backend\models\CatalogManualPage;
use backend\models\ImportForm;
use common\components\AppHelper;
use Yii;
use backend\models\CatalogProduct;
use backend\models\CatalogProductSearch;
use backend\models\CatalogCategory;
use backend\models\CatalogProductImage;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogProductController implements the CRUD actions for CatalogProduct model.
 */
class CatalogProductController extends Controller
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
     * Lists all CatalogProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CatalogProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogProduct model.
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
     * Добавление товара
     *
     * @param integer|null $id
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate($id = null)
    {
        $model = new CatalogProduct();
        $model->category_id = $id;
        $category = $id ? $this->findCategoryModel($id) : null;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
            ]);
        }
    }

    /**
     * Updates an existing CatalogProduct model.
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
     * Deletes an existing CatalogProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $categoryId = $model->category_id;
        $model->delete();

        return $this->redirect(['/catalog-category/index', 'id' => $categoryId]);
    }

    /**
     * Finds the CatalogProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Товар не найден.');
        }
    }

    /**
     * Finds the CatalogCategory model based on its primary key value.
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
            throw new NotFoundHttpException('Родительская категория не найдена.');
        }
    }

    /**
     * Finds the CatalogProductImage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogProductImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findImageModel($id)
    {
        if (($model = CatalogProductImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Картинка не найдена.');
        }
    }

    /**
     * Удаление картинки
     *
     * @param int $id
     *
     * @return \yii\web\Response|string
     */
    public function actionDeleteImage($id)
    {
        $image = $this->findImageModel($id);
        $product = $this->findModel($image->product_id);
        $image->delete();
        $product->updateImage();

        if (Yii::$app->request->isAjax) {
            return $product->image ? AppHelper::uploadsPath() . '/' . $product::FOLDER_MEDIUM . '/' . $product->image : null;
        } else {
            return $this->goBack(['view', 'id' => $product->id]);
        }
    }

    /**
     * Установка главной картинки
     *
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionSetImage($id)
    {
        $image = $this->findImageModel($id);
        $product = $this->findModel($image->product_id);
        CatalogProductImage::updateAll(['main' => CatalogProductImage::MAIN_NO], ['product_id' => $product->id]);
        if ($image->updateAttributes(['main' => CatalogProductImage::MAIN_YES])) {
            $product->image = $image->image;
            $product->updateAttributes(['image' => $image->image]);
        }

        if (Yii::$app->request->isAjax) {
            return AppHelper::uploadsPath() . '/' . $product::FOLDER_MEDIUM . '/' . $product->image;
        } else {
            return $this->goBack(['view', 'id' => $product->id]);
        }
    }

    /**
     * Импорт товаров
     *
     * @return string
     */
    public function actionImport()
    {
        $model = new ImportForm();
        if ($model->load(Yii::$app->request->post()) && $model->import()) {
            if ($model->hasErrors('file')) {
                Yii::$app->session->setFlash('danger', $model->getFirstError('file'));
            } else {
                Yii::$app->session->setFlash('success', 'Добавлено товаров: ' . $model->counts['insert']);
                Yii::$app->session->setFlash('info', 'Обновлено товаров: ' . $model->counts['update']);
                Yii::$app->session->setFlash('warning', 'Скрыто товаров: ' . $model->counts['delete']);
                return $this->refresh();
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

	/**
	 * Поиск по страницам справочника
	 *
	 * @param null $query
	 *
	 * @return string
	 */
    public function actionSearch($query = null)
	{
		$result = [];
		if ($query) {
			/* @var CatalogManualPage[] $manualPages */
			$manualPages = CatalogManualPage::find()
				->where(['like', 'catalog_manual_page.description', $query])
				->joinWith(['category', 'manual'])
				->all();

			foreach ($manualPages as $manualPage) {
				$result[] = [
					'manual' => $manualPage->manual->title,
					'category' => $manualPage->category->title,
					'categoryId' => $manualPage->category_id,
				];
			}
		}

		return json_encode($result);
	}
}
