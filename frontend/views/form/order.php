<?php

/* @var $this \frontend\controllers\FormController */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\OrderForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

?>
<?php $form = ActiveForm::begin([
	'action' => ['form/order'],
	'id' => 'order-form',
	'options' => [
		'autocomplete' => 'off',
		'class' => 'modal-form',
	],
	'enableClientValidation' => false,
]); ?>

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

<?= $form->field($model, 'name', [
	'template' => '{input}{error}{hint}'
])->textInput(['placeholder' => 'Имя']);?>

<?= $form->field($model, 'text', [
	'template' => '{input}{error}{hint}'
])->textarea(['placeholder' => 'Запрос', 'rows' => 4]);?>

<?= Html::submitButton('Отправить', ['class' => 'site-btn', 'name' => 'order-send-button']) ?>

<?php ActiveForm::end(); ?>
