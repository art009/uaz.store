<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ShopItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->input('number', ['step' => 0.5]) ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'percent')->input('number', ['step' => 0.5, 'max' => 100, 'min' => 0]) ?>

	<?= $form->field($model, 'ignored')->checkbox() ?>

	<?= $form->field($model, 'status')->checkbox() ?>

	<?= $form->field($model, 'site_price')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
