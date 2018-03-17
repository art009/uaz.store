<?php

use backend\models\Order;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
if ($searchModel->user_id) {
	$this->params['breadcrumbs'][] = ['label' => 'Все заказы', 'url' => ['index']];
	$this->title = 'Заказы пользователя #' . $searchModel->user_id;
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <h1><?= Html::encode($this->title) ?></h1>
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
				'attribute' => 'status',
				'value' => function ($model) {
					/* @var $model \backend\models\Order */
					return $model->getStatusName();
				},
				'filter' => Order::$statusList,
			],
			[
				'attribute' => 'user_id',
				'value' => function ($model) {
					/* @var $model \backend\models\Order */
					return $model->user ? implode(' | ', [$model->user->email, $model->user->phone]) : null;
				},
				'filter' => false,
			],
			[
				'attribute' => 'sum',
				'filter' => false,
			],
			[
				'attribute' => 'delivery_sum',
				'filter' => false,
			],
            // 'delivery_type',
            // 'payment_type',
			[
				'attribute' => 'changed_at',
				'filter' => false,
			],
            // 'created_at',
            // 'updated_at',

	        [
		        'attribute' => 'Документы',
		        'format' => 'raw',
		        'value' => function ($model) {
			        /* @var $model \backend\models\Order */
			        $manager = $model->getDocumentManager();
			        $list = $manager->getDocumentList();
			        $result = [];
			        foreach ($list as $type => $label) {
			        	$errors = $manager->checkDocument($type);
				        $result[] = Html::a($label, ['document', 'id' => $model->id, 'type' => $type], ['class' => empty($errors) ? 'text-primary' : 'text-danger']);
			        }
			        return implode('<br/>', $result);
		        },
		        'filter' => false,
	        ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {cashbox}',
                'buttons' => [
                    'cashbox' => function($url, $model, $key) {
                        /* @var $model Order */
	                    return Html::a(
		                    '<span class="glyphicon glyphicon-download-alt"></span>',
		                    ['cashbox', 'id' => $model->id],
		                    [
			                    'title' => 'Отправить в кассу',
			                    'data-pjax' => 0,
		                    ]
	                    );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
