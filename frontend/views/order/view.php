<?php

use common\classes\OrderPayment;
use common\models\Order;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $order Order */
/* @var $user \common\models\User */

$this->title = 'Заказ №' . $order->id;

$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-view">
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
        <?php foreach ($items as $item): ?>
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
                <?php if ($user->isLegal()): ?>
                    <?php if ($user->inn): ?>
                        <br/>
                        ИНН: <b class="color-yellow"><?php echo $user->inn; ?></b>
                    <?php endif; ?>
                    <?php if ($user->kpp): ?>
                        <br/>
                        КПП: <b class="color-yellow"><?php echo $user->kpp; ?></b>
                    <?php endif; ?>
                    <?php if ($user->bank_name): ?>
                        <br/>
                        Наименование банка ЮЛ: <b class="color-yellow"><?php echo $user->bank_name; ?></b>
                    <?php endif; ?>
                    <?php if ($user->bik): ?>
                        <br/>
                        БИК: <b class="color-yellow"><?php echo $user->bik; ?></b>
                    <?php endif; ?>
                    <?php if ($user->account_number): ?>
                        <br/>
                        Расчетный счет ЮЛ: <b class="color-yellow"><?php echo $user->account_number; ?></b>
                    <?php endif; ?>
                    <?php if ($user->correspondent_account): ?>
                        <br/>
                        Корреспондентский счет ЮЛ: <b
                                class="color-yellow"><?php echo $user->correspondent_account; ?></b>
                    <?php endif; ?>
                    <?php if ($user->kpp): ?>
                        <br/>
                        ФИО уполномоченного представителя: <b
                                class="color-yellow"><?php echo $user->representive_name; ?></b>
                    <?php endif; ?>
                    <?php if ($user->representive_position): ?>
                        <br/>
                        Должность уполномоченного представителя: <b
                                class="color-yellow"><?php echo $user->representive_position; ?></b>
                    <?php endif; ?>
                <?php endif; ?>
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
                <span class="total summary">Итого: <b class="color-yellow"><?php echo number_format($order->getTotal(),
                            2, '.', ' '); ?></b> руб</span>
                <br/>
                <br/>
            </div>
        </form>
        <?php if ($order->payment_type == Order::PAYMENT_CARD && $order->status == Order::STATUS_PAYMENT_WAITING): ?>
            <div class="payment-container">
                <?php echo OrderPayment::inlineForm($order->user_id, $order->id, $order->getTotal(), $user->phone); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
