<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ManualProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manual-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'manual_category_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'left') ?>

    <?php // echo $form->field($model, 'top') ?>

    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'positions') ?>

    <?php // echo $form->field($model, 'hide') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
