<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Notice;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			[
				'attribute' => 'id',
				'filter' => false,
			],
			[
				'attribute' => 'type',
				'value' => function(Notice $model) {
    	            return $model->getTypeName();
				},
				'filter' => Notice::$typeList,
			],
			[
				'attribute' => 'status',
				'value' => function(Notice $model) {
					return $model->getStatusName();
				},
				'filter' => Notice::$statusList,
			],
            'created_at',
            'updated_at',
            [
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
