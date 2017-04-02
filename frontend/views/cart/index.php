<?php

/* @var $this yii\web\View */
/* @var $cart \frontend\components\Cart */

use yii\bootstrap\Html;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($cart->isEmpty): ?>
        <p>Корзина пуста</p>
    <?php else: ?>
	    <?php $items = $cart->getProducts(); ?>
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
		        <tr>
			        <td><?php echo $item->getCode(); ?></td>
			        <td class="image"><?php echo $item->getImage() ?: Html::icon('camera'); ?></td>
			        <td class="title"><?php echo $item->getTitle(); ?></td>
			        <td class="price"><?php echo $item->getPrice(); ?></td>
			        <td><?php echo $item->getQuantity(); ?></td>
			        <td class="total"><?php echo $item->getTotal(); ?></td>
		        </tr>
		        <?php endforeach; ?>
		    </tbody>
	    </table>
	    <div class="summary">
		    <div class="pull-left">
			    <a href="/cart/clear">Очистить корзину</a>
		    </div>
		    <div class="pull-right">
			    <span>Итого: <b><?php echo number_format($cart->sum, 2, '.', ' '); ?></b> руб</span>
			    <a href="/order/create" class="btn site-btn">Оформить заказ</a>
		    </div>
	    </div>
    <?php endif; ?>
</div>
