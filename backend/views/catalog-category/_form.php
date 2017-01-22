<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\CatalogCategory */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {

    $this->registerJs(<<<JS

    $('#catalogcategory-title').syncTranslit({destination: 'catalogcategory-link'});

JS
, yii\web\View::POS_READY);

}

?>

<div class="catalog-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>
    <?= $form->field($model, 'parent_id')->dropDownList(
        $model::getTreeView(null, null, $model->isNewRecord ? 0 : $model->id),
        ['prompt' => 'Выберите категорию']
    ) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-catalogcategory-image">
        <?php if ($model->image): ?>
        <?= Html::activeLabel($model, 'image'); ?>
        <br/>
        <?= Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->image); ?>
        <br/>
        <?php endif; ?>
        <?= Html::label($model->getAttributeLabel('imageFile')); ?>
        <?= $form->field($model, 'imageFile', ['template' => '{input}{error}'])->fileInput(['accept' => 'image/*']) ?>
    </div>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'hide')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
