<?php

use backend\models\Manual;
use common\components\AppHelper;
use common\models\ManualCategory;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ManualCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $manual Manual */
/* @var $category ManualCategory */

if ($category) {
	$this->title = $category->title;
	$this->params['breadcrumbs'] = $category->createBackendBreadcrumbs();
} else {
	$this->title = 'Категории справочника ' . $manual->title;
	$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['manual/index']];
	$this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="manual-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить категорию', ['create', 'manualId' => $manual->id, 'categoryId' => $category ? $category->id : null], ['class' => 'btn btn-success']) ?>
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
		        'value' => function ($model) use ($manual) {
			        /* @var $model \common\models\ManualCategory */
			        $url = $model->isImageLevel() ? ['view', 'id' => $model->id] : ['index', 'manualId' => $manual->id, 'categoryId' => $model->id];
			        return Html::a($model->title, $url, ['data-pjax' => 0]);
		        },
	        ],
	        'link',
	        [
		        'attribute' => 'hide',
		        'value' => function ($model) {
			        /* @var $model \common\models\CatalogCategory */
			        return AppHelper::$yesNoList[$model->hide];
		        },
		        'filter' => AppHelper::$yesNoList,
	        ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
