<?php

/* @var $this yii\web\View */
/* @var $order \common\models\Order */
/* @var $confirmForm \frontend\models\ConfirmOrderForm */

use common\models\Order;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = $this->title;

$deliverySumHtml = Html::tag('b', number_format($order->delivery_sum, 2, '.', ' ')) . ' руб';
$freeDeliveryType = Order::DELIVERY_PICKUP;

$this->registerJs(<<<JS

    $(document).on('change', '#confirmorderform-delivery', function() {
    	var cont = $('span.delivery-price');
        if (this.value == $freeDeliveryType) {
        	$(cont).html('<b>бесплатно</b>');
        } else {
        	$(cont).html('$deliverySumHtml');
        }
    });

JS
	, yii\web\View::POS_READY);

?>
<div class="cart-index">
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
	<br/>
	<div class="pull-left">
		<a href="/order/reject" data-confirm="Вы действительно хотите отменить заказ?">Отменить заказ</a>
	</div>
	<div class="pull-right">
		<?php $form = ActiveForm::begin([
			'id' => 'order-form',
			'action' => '/order/confirm',
			'options' => ['autocomplete' => 'off'],
			'enableClientValidation' => false,
		]); ?>

		<?= $form->field($confirmForm, 'name', [
			'template' => '{input}{error}{hint}'
		])->textInput([
			'placeholder' => $confirmForm->getAttributeLabel('name'),
		]) ?>

		<?= $form->field($confirmForm, 'phone', [
			'template' => '{input}{error}{hint}'
		])->widget(MaskedInput::className(), [
			'mask' => '+7(999)999-99-99',
			'options' => [
				'class' => 'form-control tel_input',
				'placeholder' => $confirmForm->getAttributeLabel('phone'),
			],
			'clientOptions' => [
				'clearIncomplete' => false
			],
		]);?>

		<?= $form->field($confirmForm, 'email', [
			'template' => '{input}{error}{hint}'
		])->textInput([
			'placeholder' => $confirmForm->getAttributeLabel('email'),
		]) ?>

		<?= $form->field($confirmForm, 'delivery', [
			'template' => '{input}{error}{hint}'
		])->dropDownList($order::$deliveryList, ['prompt' => $confirmForm->getAttributeLabel('delivery')]) ?>

		<?= $form->field($confirmForm, 'payment', [
			'template' => '{input}{error}{hint}'
		])->dropDownList($order::$paymentList, ['prompt' => $confirmForm->getAttributeLabel('payment')]) ?>

        <?php if ($confirmForm->isLegal): ?>
            <?php if ($confirmForm->getIsLegalIp()) {
                echo $form->field($confirmForm, 'inn', [
                    'template' => '{input}{error}{hint}'
                ])->widget(MaskedInput::className(), [
                    'options' => [
                        'class' => 'form-control tel_input',
                        'placeholder' => $confirmForm->getAttributeLabel('inn') . ' (12 цифр)',
                    ],
                    'clientOptions' => [
                        'alias' => '999 999 999 999',
                    ],
                ]);
            } else {
                echo $form->field($confirmForm, 'inn', [
                    'template' => '{input}{error}{hint}'
                ])->widget(MaskedInput::className(), [
                    'options' => [
                        'class' => 'form-control tel_input',
                        'placeholder' => $confirmForm->getAttributeLabel('inn') . ' (10 цифр)',
                    ],
                    'clientOptions' => [
                        'alias' => '999 999 9999',
                    ],
                ]);
            } ?>

            <?php echo $form->field($confirmForm, 'kpp', [
                'template' => '{input}{error}{hint}'
            ])->widget(MaskedInput::className(), [
                'mask' => '999 999 999',
                'options' => [
                    'class' => 'form-control tel_input',
                    'placeholder' => $confirmForm->getAttributeLabel('kpp'),
                ],
                'clientOptions' => [
                    'clearIncomplete' => false
                ],
            ]); ?>

            <?= $form->field($confirmForm, 'bank_name', [
                'template' => '{input}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control business'
                ]
            ])->textInput([
                'placeholder' => $confirmForm->getAttributeLabel('bank_name'),
            ]) ?>

            <?= $form->field($confirmForm, 'bik', [
                'template' => '{input}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control business'
                ]
            ])->textInput([
                'placeholder' => $confirmForm->getAttributeLabel('bik'),
            ]) ?>

            <?= $form->field($confirmForm, 'account_number', [
                'template' => '{input}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control business'
                ]
            ])->textInput([
                'placeholder' => $confirmForm->getAttributeLabel('account_number'),
            ]) ?>

            <?= $form->field($confirmForm, 'correspondent_account', [
                'template' => '{input}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control business'
                ]
            ])->textInput([
                'placeholder' => $confirmForm->getAttributeLabel('correspondent_account'),
            ]) ?>

            <?= $form->field($confirmForm, 'representive_name', [
                'template' => '{input}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control business'
                ]
            ])->textInput([
                'placeholder' => $confirmForm->getAttributeLabel('representive_name'),
            ]) ?>

            <?= $form->field($confirmForm, 'representive_position', [
                'template' => '{input}{error}{hint}',
                'inputOptions' => [
                    'class' => 'form-control business'
                ]
            ])->textInput([
                'placeholder' => $confirmForm->getAttributeLabel('representive_position'),
            ]) ?>
        <?php endif; ?>

		<div class="form-group">
			Стоимость заказа: <b><?php echo number_format($order->sum, 2, '.', ' '); ?></b> руб
			<br/>
			Стоимость доставки:
			<span class="delivery-price">
				<?php if ($order->delivery_sum > 0): ?>
					<?php echo $deliverySumHtml; ?>
				<?php else: ?>
					<b>бесплатно</b>
				<?php endif; ?>
			</span>
			<br/>
			<span class="total summary">Итого: <b><?php echo number_format($order->getTotal(), 2, '.', ' '); ?></b> руб</span>
		</div>

		<?= Html::submitButton('Подтвердить заказ', ['class' => 'site-btn', 'name' => 'confirm-order-button']) ?>

		<?php ActiveForm::end(); ?>
	</div>
</div>
