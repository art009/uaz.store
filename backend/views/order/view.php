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
$this->params['breadcrumbs'][] = [
    'label' => 'Все заказы',
    'url' => ['index']
];
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
                    [
                        'change-status',
                        'id' => $order->id,
                        'status' => $status
                    ],
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
        <?php foreach ($items as $item): ?>
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
                    'style' => 'display: ' . (array_key_exists(
                            'CatalogProductSearch',
                            Yii::$app->request->queryParams
                        ) ? 'block' : 'none'),
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
                Факс: <b class="color-yellow"><?php echo $user->fax; ?></b>
                <br/>
                E-mail: <b class="color-yellow"><?php echo $user->email; ?></b>
                <?php if (!$user->isLegal()): ?>
                    <br/>
                    Серия паспорта: <b class="color-yellow"><?php echo $user->passport_series; ?></b>
                    <br/>
                    Номер паспорта: <b class="color-yellow"><?php echo $user->passport_number; ?></b>
                <?php endif; ?>
                <br/>
                Индекс: <b class="color-yellow"><?php echo $user->postcode; ?></b>
                <br/>
                Адрес: <b class="color-yellow"><?php echo $user->address; ?></b>

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
                <br>
                <?= Html::a(
                    'Отредактировать покупателя',
                    [
                        'user-order/update',
                        'id' => $user->id
                    ],
                    ['class' => 'btn btn-success']
                )
                ?>
            </div>
            <div class="form-group">
                Способ доставки:
                <b class="color-yellow"><?php echo Order::$deliveryList[$order->delivery_type] ?? 'Не указано'; ?></b>
                <br/>
                Вариант оплаты:
                <b class="color-yellow"><?php echo Order::$paymentList[$order->payment_type] ?? 'Не указано'; ?></b>
                <?php if ($order->payment_type == Order::PAYMENT_CARD): ?>
                <br/>
                Ссылка на оплату:
                <?php $link = Yii::$app->params['frontendUrl'].'order/view?id='.$order->id; ?>
                <a href="<?= $link ?>"><?= $link ?></a>
                <?php endif; ?>
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
                Стоимость отправки:
                <?php if ($order->sending_cost > 0): ?>
                    <b class="color-yellow"><?php echo number_format($order->sending_cost, 2, '.', ' '); ?></b> руб
                <?php else: ?>
                    <b class="color-yellow">не учитывается</b>
                <?php endif; ?>
                <br/>
                <?php if ($order->sale_percent > 0): ?>
                    Скидка:
                    <b class="color-yellow"><?php echo $order->sale_percent; ?></b> %
                <?php endif; ?>
                <br/>
                <h3 class="total summary">Итого: <b class="color-yellow"><?php echo number_format(
                            $order->getTotal(),
                            2,
                            '.',
                            ' '
                        ); ?></b> руб</h3>
                <?php if ($order->sale_percent > 0): ?>
                <h3 class="total summary">Итого co скидкой: <b class="color-yellow"><?php echo number_format(
                            $order->getTotalWithDiscount(),
                            2,
                            '.',
                            ' '
                        ); ?></b> руб</h3>
                <?php endif; ?>
                <?= Html::a(
                    'Изменить параметры заказа',
                    [
                        'order/update',
                        'id' => $order->id
                    ],
                    ['class' => 'btn btn-success']
                )
                ?>
            </div>
        </form>
    </div>
</div>
<?php Pjax::end(); ?>

