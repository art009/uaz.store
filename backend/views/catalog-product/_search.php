<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CatalogProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'link') ?>

    <?= $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'price_to') ?>

    <?php // echo $form->field($model, 'price_old') ?>

    <?php // echo $form->field($model, 'shop_title') ?>

    <?php // echo $form->field($model, 'provider_title') ?>

    <?php // echo $form->field($model, 'shop_code') ?>

    <?php // echo $form->field($model, 'provider_code') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'hide') ?>

    <?php // echo $form->field($model, 'on_main') ?>

    <?php // echo $form->field($model, 'provider') ?>

    <?php // echo $form->field($model, 'manufacturer') ?>

    <?php // echo $form->field($model, 'cart_counter') ?>

    <?php // echo $form->field($model, 'length') ?>

    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'rest') ?>

    <?php // echo $form->field($model, 'external_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
