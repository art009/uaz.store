<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ProviderItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provider-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'provider_id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'vendor_code') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'manufacturer') ?>

    <?php // echo $form->field($model, 'rest') ?>

    <?php // echo $form->field($model, 'ignored') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
