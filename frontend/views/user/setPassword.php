<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Установка нового пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-set-password">
    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(['id' => 'user-set-password-form']); ?>
	<div class="form-header">
		<div class="col-xs-6 text-center">Установка пароля</div>
		<div class="col-xs-6 text-center"><a href="/login">Вход</a></div>
	</div>
	<div class="clearfix"></div>

	<?= $form->field($model, 'password', [
		'template' => '{input}{error}{hint}'
	])->passwordInput([
		'autofocus' => true,
		'placeholder' => $model->getAttributeLabel('password'),
	]) ?>

	<?= Html::submitButton('Сохранить', ['class' => 'site-btn', 'name' => 'save-button']) ?>

	<?php ActiveForm::end(); ?>
</div>
