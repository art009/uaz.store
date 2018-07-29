<?php

use common\models\Order;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $order Order */
/* @var $user \common\models\User */

$this->title = 'Заказ №' . $order->id;

$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
	<?php $items = $order->orderProducts; ?>
	<table>
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
						echo Html::a(Html::icon('camera'), null, [
							'data-tooltip' => 'tooltip-image',
							'title' => Html::img($item->getImage()),
						]);
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
				<br/>
				Статус: <b class="color-yellow"><?php echo $order->getStatusName(); ?></b>
			</div>
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
				Стоимость доставки: <b class="color-yellow">после согласования</b>
				<br/>
				<span class="total summary">Итого: <b class="color-yellow"><?php echo number_format($order->getTotal(), 2, '.', ' '); ?></b> руб</span>
				<br/>
				<br/>
			</div>
		</form>
	</div>
</div>
