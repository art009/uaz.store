<?php

use backend\models\Order;
use common\classes\OrderStatusWorkflow;
use common\components\AppHelper;
use common\widgets\Alert;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $order Order */
/* @var $productSearch backend\models\CatalogProductSearch */
/* @var $productProvider yii\data\ActiveDataProvider */

$this->title = 'Заказ №' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Все заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$items = $order->orderProducts;
$user = $order->user;
$availableStatuses = OrderStatusWorkflow::statusList($order->status);

if ($order->status == Order::STATUS_PROCESS) {
	$this->registerJs(<<<JS

    $(document).on('click', '.product-search-toggle', function() {
        $(this).next().toggle();
        return false;
    });

JS
		, yii\web\View::POS_READY);
}

?>
<?php Pjax::begin([
	'id' => 'backend-order-view',
	'timeout' => false,
	'enablePushState' => false,
]); ?>
<?php if (Yii::$app->request->isPjax): ?>
	<?php echo Alert::widget() ?>
<?php endif; ?>
<div class="order-view">
    <h1><?= Html::encode($this->title) ?></h1>
	<div>
		<code>
			Создан <b><?php echo $order->created_at; ?></b>
		</code>
		<br/>
		<code>
			Изменен <b><?php echo $order->updated_at; ?></b>
		</code>
		<br/>
		<h1>Статус <span class="label label-default"><?php echo $order->getStatusName(); ?></span></h1>
	</div>
	<?php if ($availableStatuses): ?>
	<br/>
	<div>
		Перевод в статус:
		<?php foreach ($availableStatuses as $status): ?>
			<?php echo Html::a(
				Order::statusName($status),
				['change-status', 'id' => $order->id, 'status' => $status],
				['class' => 'btn btn-primary']
			); ?>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<br/>
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>Артикул</th>
			<th>Фото</th>
			<th>Название</th>
			<th>Цена</th>
			<th>Кол-во</th>
			<th>Стоимость</th>
			<?php if ($order->status == Order::STATUS_PROCESS): ?>
			<th>Действия</th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item): ?>
			<tr data-id="<?php echo $item->getProductId(); ?>">
				<td><?php echo $item->getCode(); ?></td>
				<td class="image">
					<?php
					if ($item->getImage()) {
						echo Html::img($item->getImage(), ['height' => 40]);
					} else {
						echo Html::icon('camera');
					}
					?>
				</td>
				<td class="title"><?php echo $item->getTitle(); ?></td>
				<td class="price"><?php echo $item->getPrice(); ?></td>
				<td class="quantity" style="white-space: nowrap; text-align: center">
					<?php if ($order->status == Order::STATUS_PROCESS && $item->getQuantity() > 1): ?>
						<?php echo Html::a(Html::icon('minus'), [
							'dec-product',
							'orderId' => $order->id,
							'productId' => $item->product_id
						]) ?>
					<?php endif; ?>
					<?php echo $item->getQuantity(); ?>
					<?php if ($order->status == Order::STATUS_PROCESS): ?>
						<?php echo Html::a(Html::icon('plus'), [
							'inc-product',
							'orderId' => $order->id,
							'productId' => $item->product_id
						]) ?>
					<?php endif; ?>
				</td>
				<td class="total"><?php echo $item->getTotal(); ?></td>
				<?php if ($order->status == Order::STATUS_PROCESS): ?>
				<td>
					<?php echo Html::a('Удалить', [
						'delete-product',
						'orderId' => $order->id,
						'productId' => $item->product_id
					]) ?>
				</td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php if ($order->status == Order::STATUS_PROCESS): ?>
	<div>
	<a href="#" class="product-search-toggle">Показать/скрыть поиск товаров</a>
	<?= GridView::widget([
		'id' => 'order-view-product-search',
		'options' => [
			'class' => 'grid-view',
			'style' => 'display: ' . (array_key_exists('CatalogProductSearch', Yii::$app->request->queryParams) ? 'block' : 'none'),
		],
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
			'shop_code',
			'price',
			[
				'class' => 'yii\grid\ActionColumn',
				'options' => [
					'width' => '70px;'
				],
				'template' => '{apply}',
				'buttons' => [
					'apply' => function ($url, $model, $key) use ($order) {
						return Html::a('Добавить', [
							'add-product',
							'orderId' => $order->id,
							'productId' => $model->id,
						]);
					},
				]
			],
		],
	]); ?>
	</div>
	<br/>
	<?php endif; ?>
	<div class="pull-left">
		<form>
			<div class="form-group">
				Контактное лицо: <b class="color-yellow"><?php echo $user->name; ?></b>
				<br/>
				Телефон: <b class="color-yellow">+7<?php echo $user->phone; ?></b>
				<br/>
				E-mail: <b class="color-yellow"><?php echo $user->email; ?></b>
			</div>
			<div class="form-group">
				Способ доставки:
				<b class="color-yellow"><?php echo Order::$deliveryList[$order->delivery_type] ?? 'Не указано'; ?></b>
				<br/>
				Вариант оплаты:
				<b class="color-yellow"><?php echo Order::$paymentList[$order->payment_type] ?? 'Не указано'; ?></b>
			</div>
			<div class="form-group">
				Стоимость заказа: <b class="color-yellow"><?php echo number_format($order->sum, 2, '.', ' '); ?></b> руб
				<br/>
				Стоимость доставки:
				<?php if ($order->delivery_sum > 0): ?>
					<b class="color-yellow"><?php echo number_format($order->delivery_sum, 2, '.', ' '); ?></b> руб
				<?php else: ?>
					<b class="color-yellow">бесплатно</b>
				<?php endif; ?>
				<br/>
				<h3 class="total summary">Итого: <b class="color-yellow"><?php echo number_format($order->getTotal(), 2, '.', ' '); ?></b> руб</h3>
			</div>
		</form>
	</div>
</div>
<?php Pjax::end(); ?>

