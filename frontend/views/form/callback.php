<?php

/* @var $this \frontend\controllers\FormController */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\CallbackForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

?>
<?php $form = ActiveForm::begin([
	'action' => ['form/callback'],
	'id' => 'callback-form',
	'options' => [
		'autocomplete' => 'off',
		'class' => 'modal-form',
	],
	'enableClientValidation' => false,
]); ?>

<?= $form->field($model, 'name', [
	'template' => '{input}{error}{hint}',
	'inputOptions' => [
		'class' => 'form-control tel_input',
		'placeholder' => $model->getAttributeLabel('name'),
	],
])->textInput(); ?>

<?= $form->field($model, 'phone', [
	'template' => '{input}{error}{hint}'
])->widget(MaskedInput::className(), [
	'mask' => '+7(999)999-99-99',
	'options' => [
		'class' => 'form-control tel_input',
		'placeholder' => $model->getAttributeLabel('phone'),
	],
	'clientOptions' => [
		'clearIncomplete' => false
	],
]);?>

<?= Html::submitButton('Заказать', ['class' => 'site-btn', 'name' => 'signup-button']) ?>

<?php ActiveForm::end(); ?>
