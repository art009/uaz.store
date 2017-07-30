<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Manual */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {

$this->registerJs(<<<JS

    $('#Manual-title').syncTranslit({destination: 'Manual-link'});

JS
	, yii\web\View::POS_READY);
}

?>

<div class="catalog-manual-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

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

	<?= $form->field($model, 'year')->input('number');?>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'hide')->checkbox() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
