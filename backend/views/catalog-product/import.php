<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\ImportForm */

$this->title = 'Импорт товаров';
$this->params['breadcrumbs'][] = ['label' => 'Категории товаров', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <b>Для корректности импорта следуют соблюдать следующие правила</b>:<br/>
        1. Убрать заголовки столбцов, пустые строки и формулы в ячейках<br/>
        2. В каждом столбце должен соответствующий атрибут<br/>
        <code><b>A</b> - Код синхронизации</code><br/>
        <code><b>B</b> - Название на сайте</code><br/>
        <code><b>C</b> - Название в магазине</code><br/>
        <code><b>D</b> - Название у поставщика</code><br/>
        <code><b>E</b> - Артикул в магазине</code><br/>
        <code><b>F</b> - Артикул на сайте</code><br/>
        <code><b>G</b> - Цена на сайте</code><br/>
        <code><b>H</b> - Единица измерения</code><br/>
        <code><b>I</b> - Производитель</code>
    </div>
    <br/>
    <div class="catalog-product-import-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'file')->fileInput(['accept' => '.xls,.xlsx,.csv']) ?>

        <div class="form-group">
            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
