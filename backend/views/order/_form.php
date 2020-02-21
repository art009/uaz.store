<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeLabel($model, 'delivery_type'); ?>
    <?= $form->field($model, 'delivery_type', [
        'template' => '{input}{error}{hint}'
    ])->dropDownList($model::$deliveryList, ['prompt' => $model->getAttributeLabel('delivery_type')]) ?>

    <?= Html::activeLabel($model, 'delivery_type'); ?>
    <?= $form->field($model, 'payment_type', [
        'template' => '{input}{error}{hint}'
    ])->dropDownList($model::$paymentList, ['prompt' => $model->getAttributeLabel('payment_type')]) ?>

    <?= $form->field($model, 'sending_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sale_percent')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
