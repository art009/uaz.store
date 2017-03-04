<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление доступа';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'user-reset-password-form']); ?>
		<div class="form-header">
			<div class="col-xs-7 text-center">Восстановление доступа</div>
			<div class="col-xs-5 text-center"><a href="/login">Вход</a></div>
		</div>
		<div class="clearfix"></div>

		<?= $form->field($model, 'email', [
			'template' => '{input}{error}{hint}'
		])->textInput([
			'autofocus' => true,
			'placeholder' => $model->getAttributeLabel('email'),
		]) ?>

		<?= Html::submitButton('Отправить', ['class' => 'site-btn', 'name' => 'send-button']) ?>

	<?php ActiveForm::end(); ?>
</div>
