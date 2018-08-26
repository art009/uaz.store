<?php

use backend\models\Order;
use common\classes\OrderStatusWorkflow;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model Order */

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Все заказы', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => 'Заказы пользователя #' . $model->user_id, 'url' => ['index', 'userId' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;

$items = $model->orderProducts;
$user = $model->user;

?>
<div class="order-view">
    <h1><?= Html::encode($this->title) ?></h1>
	<div>
		<code>
			Создан <b><?php echo $model->created_at; ?></b>
		</code>
		<br/>
		<code>
			Изменен <b><?php echo $model->updated_at; ?></b>
		</code>
		<br/>
		<h1>Статус <span class="label label-default"><?php echo $model->getStatusName(); ?></span></h1>
	</div>
	<br/>
	<div>
		Перевод в статус:
		<?php foreach (OrderStatusWorkflow::statusList($model->status) as $status): ?>
			<?php echo Html::a(
				Order::statusName($status),
				['change-status', 'id' => $model->id, 'status' => $status],
				['class' => 'btn btn-primary']
			); ?>
		<?php endforeach; ?>
	</div>
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
				<td class="quantity"><?php echo $item->getQuantity(); ?></td>
				<td class="total"><?php echo $item->getTotal(); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
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
				<b class="color-yellow"><?php echo Order::$deliveryList[$model->delivery_type] ?? 'Не указано'; ?></b>
				<br/>
				Вариант оплаты:
				<b class="color-yellow"><?php echo Order::$paymentList[$model->payment_type] ?? 'Не указано'; ?></b>
			</div>
			<div class="form-group">
				Стоимость заказа: <b class="color-yellow"><?php echo number_format($model->sum, 2, '.', ' '); ?></b> руб
				<br/>
				Стоимость доставки:
				<?php if ($model->delivery_sum > 0): ?>
					<b class="color-yellow"><?php echo number_format($model->delivery_sum, 2, '.', ' '); ?></b> руб
				<?php else: ?>
					<b class="color-yellow">бесплатно</b>
				<?php endif; ?>
				<br/>
				<h3 class="total summary">Итого: <b class="color-yellow"><?php echo number_format($model->getTotal(), 2, '.', ' '); ?></b> руб</h3>
			</div>
		</form>
	</div>
</div>
