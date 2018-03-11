<?php

use common\components\AppHelper;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-manual-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить справочник', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
					return Html::a($model->title, ['view', 'id' => $model->id], ['data-pjax' => 0]);
				},
			],
            'link',
			[
				'attribute' => 'image',
				'format' => 'raw',
				'value' => function ($model) {
					/* @var $model \backend\models\CatalogProduct */
					return $model->image ? Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_MEDIUM . '/' . $model->image) : null;
				},
			],
			[
				'attribute' => 'hide',
				'value' => function ($model) {
					/* @var $model \common\models\CatalogCategory */
					return AppHelper::$yesNoList[$model->hide];
				},
			],
			[
				'attribute' => 'Категории',
				'format' => 'raw',
				'value' => function ($model) {
					/* @var $model \common\models\Manual */
					return Html::a('Список', ['manual-category/index', 'manualId' => $model->id], ['data-pjax' => 0]);
				},
			],
            ['class' => 'yii\grid\ActionColumn',

                'template' => '{view}{update}'
            ],
        ],
    ]); ?>
</div>
