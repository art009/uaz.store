<?php

use backend\models\CatalogCategory;
use common\components\AppHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ManualCategory */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {

	$this->registerJs(<<<JS

    $('#manualcategory-title').syncTranslit({destination: 'manualcategory-link'});

JS
		, yii\web\View::POS_READY);

}

?>

<div class="manual-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

	<?php if ($model->isImageLevel()): ?>
	<?= $form->field($model, 'catalog_category_id')->dropDownList(
		CatalogCategory::getTreeView(null, null, $model->isNewRecord ? 0 : $model->id),
		['prompt' => 'Выберите категорию каталога']
	) ?>
	<?php endif; ?>

    <?= $form->field($model, 'hide')->checkbox() ?>

	<?php if ($model->isImageLevel()): ?>
	<div class="form-group field-catalogcategory-image">
		<?php if ($model->image): ?>
			<?= Html::activeLabel($model, 'image'); ?>
			<br/>
			<?= Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_MEDIUM . '/' . $model->image); ?>
			<br/>
		<?php endif; ?>
		<?= Html::label($model->getAttributeLabel('imageFile')); ?>
		<?= $form->field($model, 'imageFile', ['template' => '{input}{error}'])->fileInput(['accept' => 'image/*']) ?>
	</div>
	<?php endif; ?>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
