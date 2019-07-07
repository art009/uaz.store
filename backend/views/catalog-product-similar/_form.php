<?php

use common\models\CatalogProductSimilar;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogProductSimilar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-product-similar-form">

    <?php $form = ActiveForm::begin(); ?>
    <label for="filter-product_id">Продукт</label>
    <?= Select2::widget([
        'id' => 'filter-product_id',
        'name' => 'CatalogProductSimilar[product_id]',
        'options' => [
            'placeholder' => 'Выберите продукт',
            'disabled' => $model->isNewRecord ? false : true
        ],
        'value' => $model->isNewRecord ? '' : $model->product_id,
        'initValueText' => $model->isNewRecord ? '' : $model->product->title,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Загрузка...'; }"),
            ],
            'ajax' => [
                'url' => '/catalog-product-similar/filter-catalog-product',
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(res) { return res.text; }'),
            'templateSelection' => new JsExpression('function (res) { return res.text; }'),
        ]
    ]); ?>

    <label for="filter-product_id">Аналогичный продукт</label>
    <?= Select2::widget([
        'id' => 'filter-similar-product_id',
        'name' => 'CatalogProductSimilar[similar_product_id]',
        'options' => [
            'placeholder' => 'Выберите продукт'
        ],
        'value' => $model->isNewRecord ? '' : $model->similar_product_id,
        'initValueText' => $model->isNewRecord ? '' : $model->similarProduct->title,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Загрузка...'; }"),
            ],
            'ajax' => [
                'url' => '/catalog-product-similar/filter-catalog-product',
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(res) { return res.text; }'),
            'templateSelection' => new JsExpression('function (res) { return res.text; }'),
        ]
    ]); ?>

    <div class="form-group">
        <br>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
