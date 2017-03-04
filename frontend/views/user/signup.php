<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-signup">
    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(['id' => 'user-signup-form']); ?>
	<div class="form-header">
		<div class="col-xs-6 text-center">Регистрация</div>
		<div class="col-xs-6 text-center"><a href="/login">Вход</a></div>
	</div>
	<div class="clearfix"></div>

	<?= $form->field($model, 'username', [
		'template' => '{input}{error}{hint}'
	])->textInput([
		'autofocus' => true,
		'placeholder' => $model->getAttributeLabel('username'),
	]) ?>

	<?= $form->field($model, 'email', [
		'template' => '{input}{error}{hint}'
	])->textInput([
		'autofocus' => true,
		'placeholder' => $model->getAttributeLabel('email'),
	]) ?>

	<?= $form->field($model, 'password', [
		'template' => '{input}{error}{hint}'
	])->passwordInput([
		'placeholder' => $model->getAttributeLabel('password')
	]) ?>

	<?= Html::submitButton('Зарегистрироваться', ['class' => 'site-btn', 'name' => 'signup-button']) ?>

	<?php ActiveForm::end(); ?>
</div>
