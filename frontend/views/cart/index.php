<?php

/* @var $this yii\web\View */
/* @var $cart \frontend\components\Cart */

use yii\helpers\Html;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($cart->isEmpty): ?>
        <p>Корзина пуста</p>
    <?php else: ?>
        <p>В корзине товаров: <?php echo $cart->quantity; ?> на сумму <?php echo $cart->sum; ?> руб </p>
        <p>
            <a href="/cart/clear" class="btn btn-warning">Очистить корзину</a>
        </p>
    <?php endif; ?>

    <a href="/cart/add?productId=1" class="btn btn-default">Добавить тестовый товар</a>
    <a href="/cart/add?productId=2" class="btn btn-info">Добавить другой тестовый товар</a>
    <br/>
    <br/>
    <a href="/cart/add?productId=1&quantity=2" class="btn btn-default">Добавить 2 тестовых товара</a>
    <a href="/cart/add?productId=2&quantity=2" class="btn btn-info">Добавить 2 других тестовых товара</a>
</div>
