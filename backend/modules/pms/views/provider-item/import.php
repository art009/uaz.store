<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\modules\pms\models\ShopImportForm */

$this->title = 'Импорт товаров';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Товары поставщика', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для корректности импорта следуют убрать заголовки столбцов, пустые строки и формулы в ячейках</p>
    <div class="catalog-product-import-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'file')->fileInput(['accept' => '.xls,.xlsx,.csv']) ?>
	    <?= $form->field($model, 'title')->textInput() ?>
	    <?= $form->field($model, 'rest')->textInput() ?>
	    <?= $form->field($model, 'unit')->textInput() ?>
	    <?= $form->field($model, 'code')->textInput() ?>
	    <?= $form->field($model, 'vendor_code')->textInput() ?>
	    <?= $form->field($model, 'manufacturer')->textInput() ?>
	    <?= $form->field($model, 'price')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
