<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \common\models\Order */

?>

<div class="order-list__item_row clearfix">
    <div class="pull-left">
        Заказ №<?php echo $model->id; ?>
    </div>
    <div class="pull-right">
        <?php echo Html::a('Посмотреть', ['view', 'id' => $model->id], ['class' => 'site-btn']) ?>
    </div>
</div>
<div class="order-list__item_row clearfix">
    Статус: <span><b>&nbsp;&bull;&nbsp;</b> <?php echo $model->getStatusName(); ?></span>
</div>
<div class="order-list__item_row clearfix">
    Стоимость: <?php echo number_format($model->getTotal(), 2, '.', ' '); ?> руб
</div>
<div class="order-list__item_row clearfix">
    Дата обновления статуса: <span><?php echo date('d.m.Y', strtotime($model->changed_at)); ?> г.</span>
</div>
