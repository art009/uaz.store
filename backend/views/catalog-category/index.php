<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CatalogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentModel \common\models\CatalogCategory */

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
        <?= Html::a('Добавить категорию', ['create', 'id' => $parentModel->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить товар', ['catalog-product/create', 'id' => $parentModel->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($parentModel == null): ?>
        <?= Html::a('Импорт товаров', ['create'], ['class' => 'btn btn-warning']) ?>
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
                    return AppHelper::$hiddenList[$model->hide];
                },
                'filter' => AppHelper::$hiddenList,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>
</div>
