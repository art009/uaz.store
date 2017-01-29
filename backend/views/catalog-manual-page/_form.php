<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use common\models\CatalogCategory;
use common\widgets\ChosenSelect;
use backend\models\CatalogManual;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogManualPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-manual-page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'manual_id')->dropDownList(CatalogManual::getListed(), [
        'prompt' => 'Выберите справочник',
        'disabled' => true,
    ]) ?>

	<?php echo $form->field($model, 'category_id')->dropDownList(CatalogCategory::getTreeView(), [
		'prompt' => 'Выберите категорию',
        'disabled' => true,
	]); ?>

	<?php /*echo $form->field($model, 'category_id')->widget(ChosenSelect::className(), [
		'placeholder' => 'Выберите категорию',
		'items' => CatalogCategory::getTreeView(),
        'options' => [
			'disabled' => true,
		]
	]);*/ ?>

    <div class="form-group field-catalogcategory-image">
		<?php if ($model->image): ?>
			<?= Html::activeLabel($model, 'image'); ?>
            <br/>
			<?= Html::a(
				Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_MEDIUM . '/' . $model->image),
				AppHelper::uploadsPath() . '/' . $model::FOLDER . '/' . $model->image,
				['data-fancybox' => true]
			); ?>
            <br/>
		<?php endif; ?>
		<?= Html::label($model->getAttributeLabel('imageFile')); ?>
		<?= $form->field($model, 'imageFile', ['template' => '{input}{error}'])->fileInput(['accept' => 'image/*']) ?>
    </div>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'description')->widget(TinyMce::className());?>

	<?= $form->field($model, 'hide')->checkbox() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
