<?php

/* @var $this \frontend\controllers\FormController */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\QuestionForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin([
	'action' => ['form/question'],
	'id' => 'question-form',
	'options' => [
		'autocomplete' => 'off',
		'class' => 'modal-form',
	],
	'enableClientValidation' => false,
]); ?>

<?= $form->field($model, 'email', [
	'template' => '{input}{error}{hint}'
])->textInput(['placeholder' => 'E-mail']);?>

<?= $form->field($model, 'name', [
	'template' => '{input}{error}{hint}'
])->textInput(['placeholder' => 'Имя']);?>

<?= $form->field($model, 'text', [
	'template' => '{input}{error}{hint}'
])->textarea(['placeholder' => 'Вопрос', 'rows' => 4]);?>

<?= Html::submitButton('Отправить', ['class' => 'site-btn', 'name' => 'question-send-button']) ?>

<?php ActiveForm::end(); ?>
