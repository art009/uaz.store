<?php

/* @var $this yii\web\View */
/* @var $cart \frontend\components\Cart */

use yii\bootstrap\Html;
use common\models\CatalogProduct;

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
			        <td class="quantity">
				        <?php echo Html::icon('minus', ['class' => 'dec-cart-product', 'data-id' => $item->getProductId()]); ?><div>
					        <?php echo $item->getQuantity(); ?>
				        </div><?php echo Html::icon('plus', ['class' => 'inc-cart-product', 'data-id' => $item->getProductId()]); ?>
			        </td>
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
			    <span class="total">Итого: <b><?php echo number_format($cart->sum, 2, '.', ' '); ?></b> руб</span>
			    <a href="/order/create" class="btn site-btn">Оформить заказ</a>
		    </div>
	    </div>
    <?php endif; ?>
</div>
