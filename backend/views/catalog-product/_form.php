<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CatalogCategory;
use common\components\AppHelper;
use common\widgets\TinyMce;

/* @var $this yii\web\View */
/* @var $model backend\models\CatalogProduct */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {

    $this->registerJs(<<<JS

    $('#catalogproduct-title').syncTranslit({destination: 'catalogproduct-link'});

JS
        , yii\web\View::POS_READY);

}
?>

<div class="catalog-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->dropDownList(CatalogCategory::getTreeView(), ['prompt' => 'Выберите категорию']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-catalogcategory-image">
        <?php if ($model->image): ?>
            <?= Html::activeLabel($model, 'image'); ?>
            <br/>
            <?= Html::a(
                Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_MEDIUM . '/' . $model->image, ['id' => 'product-main-image']),
                AppHelper::uploadsPath() . '/' . $model::FOLDER . '/' . $model->image,
                ['data-fancybox' => true]
            ); ?>
            <br/>
            <?php if ($model->images): ?>
                <?= Html::activeLabel($model, 'images'); ?>
                <br/>
                <?php echo $model->getImagesHtml(' '); ?>
                <br/>
            <?php endif; ?>
        <?php endif; ?>
        <?= Html::label($model->getAttributeLabel('imageFiles')); ?>
        <?= $form->field($model, 'imageFiles[]', ['template' => '{input}{error}'])->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    </div>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->input('number', ['step' => 0.01]) ?>

    <?= $form->field($model, 'shop_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'provider_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shop_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'provider_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manufacturer_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(TinyMce::className());?>

    <?= $form->field($model, 'hide')->checkbox() ?>

    <?= $form->field($model, 'on_main')->checkbox() ?>

    <?= $form->field($model, 'provider')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manufacturer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'length')->input('number', ['step' => 1]) ?>

    <?= $form->field($model, 'width')->input('number', ['step' => 1]) ?>

    <?= $form->field($model, 'height')->input('number', ['step' => 1]) ?>

    <?= $form->field($model, 'weight')->input('number', ['step' => 1]) ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
