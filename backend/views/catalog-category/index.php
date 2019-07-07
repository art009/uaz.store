<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CatalogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentModel \common\models\CatalogCategory */
/* @var $productSearch backend\models\CatalogProductSearch */
/* @var $productProvider yii\data\ActiveDataProvider */

$this->title = 'Категории товаров';
if ($parentModel) {
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
    if ($parentsList = $parentModel->getParentsList(true)) {
        foreach ($parentsList as $id => $title) {
            $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index', 'id' => $id]];
        }
    }
    $this->params['breadcrumbs'][] = $parentModel->title;
} else {
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="catalog-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить категорию', ['create', 'id' => $parentModel ? $parentModel->id : null],
            ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить товар', ['catalog-product/create', 'id' => $parentModel ? $parentModel->id : null],
            ['class' => 'btn btn-primary']) ?>
        <?php if ($parentModel == null): ?>
            <?= Html::a('Импорт товаров', ['catalog-product/import'], ['class' => 'btn btn-warning']) ?>
        <?php endif; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
                    'width' => '40px;'
                ]
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model \common\models\CatalogCategory */
                    return Html::a($model->title, ['index', 'id' => $model->id], ['data-pjax' => 0]);
                },
            ],
            'link',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model \common\models\CatalogCategory */
                    return $model->image ? Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->image) : null;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'hide',
                'value' => function ($model) {
                    /* @var $model \common\models\CatalogCategory */
                    return AppHelper::$yesNoList[$model->hide];
                },
                'filter' => AppHelper::$yesNoList,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>

    <h3>Товары</h3>
    <?= GridView::widget([
        'dataProvider' => $productProvider,
        'filterModel' => $productSearch,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
                    'width' => '40px;'
                ]
            ],
            [
                'attribute' => 'title',
                'options' => [
                    //'width' => '350px;'
                ],
            ],
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model \backend\models\CatalogProduct */
                    return $model->image ? Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->image) : null;
                },
                'filter' => AppHelper::$yesNoList,
            ],
            'price',
            'cart_counter',
            [
                'attribute' => 'hasCategories',
                'value' => 'hasCategoriesLabel',
                'filter' => AppHelper::$yesNoList,
            ],
            [
                'attribute' => 'hide',
                'value' => function ($model) {
                    /* @var $model \backend\models\CatalogProduct */
                    return AppHelper::$yesNoList[$model->hide];
                },
                'filter' => AppHelper::$yesNoList,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => [
                    'width' => '70px;'
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(Html::icon('eye-open'), ['/catalog-product/view', 'id' => $model->id], [
                            'title' => 'Просмотр',
                            'aria-label' => 'Просмотр',
                            'data-pjax' => 0,
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a(Html::icon('pencil'), ['/catalog-product/update', 'id' => $model->id], [
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => 0,
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(Html::icon('trash'), ['/catalog-product/delete', 'id' => $model->id], [
                            'title' => 'Удалить',
                            'aria-label' => 'Удалить',
                            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                            'data-method' => 'post',
                            'data-pjax' => 0,
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>