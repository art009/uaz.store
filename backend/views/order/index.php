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
		        'attribute' => 'Касса',
		        'format' => 'raw',
		        'value' => function ($model) {
			        /* @var $model Order */
			        $result = '';

			        if (in_array($model->status, [Order::STATUS_PICKUP, Order::STATUS_PAYMENT_DONE])) {
			        	$result .= Html::a(
					        'Отправить чек',
					        ['cash-box', 'id' => $model->id],
					        [
						        'title' => 'Отправить в кассу',
						        'aria-label' => 'Отправить в кассу',
						        'data-confirm' => 'Вы уверены, что хотите отправить информацию по заказу в кассу?',
						        'data-method' => 'post',
						        'data-pjax' => 0,
					        ]
				        );
			        }
			        //$model->cash_box_sent_at = '2019-05-01 12:12:12';
			        if (strtotime($model->cash_box_sent_at) > 0) {
			        	$result .= 'Чек отправлен: <br><b>' . $model->cash_box_sent_at . '</b><br/>';

			        	if (strtotime($model->cash_box_return_at) <= 0) {
					        $result .= Html::a(
						        'Сделать возврат',
						        ['cash-box-return', 'id' => $model->id],
						        [
							        'title' => 'Оформить возврат',
							        'aria-label' => 'Оформить возврат',
							        'data-confirm' => 'Вы уверены, что хотите оформить возврат по данному заказу?',
							        'data-method' => 'post',
							        'data-pjax' => 0,
						        ]
					        );
				        }
			        }
			        if ($model->cash_box_sent_error) {
			        	$result .= '<br/><b>Ошибка при отправке чека:</b> ' . $model->cash_box_sent_error;
			        }

			        if (strtotime($model->cash_box_return_at) > 0) {
				        $result .= '<br/>Возврат оформлен: <br><b>' . $model->cash_box_return_at . '</b><br/>';
			        }

			        if ($model->cash_box_return_error) {
				        $result .= '<br/>Ошибка при оформлении возврата: ' . $model->cash_box_return_error;
			        }

			        return $result;
		        },
		        'filter' => false,
	        ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
