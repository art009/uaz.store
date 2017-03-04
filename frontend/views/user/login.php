<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'user-form', 'options' => ['autocomplete' => 'off']]); ?>

		<div class="form-header">
			<div class="col-xs-6 text-center">Вход</div>
			<div class="col-xs-6 text-center"><a href="/signup">Регистрация</a></div>
		</div>
		<div class="clearfix"></div>

		<!-- >>> Avoid Chrome autofill >>> -->
		<?php /*echo Html::activeTextInput($model, 'username', ['class' => 'hidden']); ?>
		<?php echo Html::activePasswordInput($model, 'password', ['class' => 'hidden']);*/ ?>
		<!-- <<< Avoid Chrome autofill <<< -->

        <?= $form->field($model, 'username', [
	        'template' => '{input}{error}{hint}'
        ])->textInput([
	        'autofocus' => true,
	        'placeholder' => $model->getAttributeLabel('username')
        ]) ?>

        <?= $form->field($model, 'password', [
	        'template' => '{input}{error}{hint}'
        ])->passwordInput([
	        'placeholder' => $model->getAttributeLabel('password')
        ]) ?>

        <div class="form-group pull-right">
            <?= Html::a('Забыли пароль?', ['/password-reset']) ?>
        </div>

        <?= Html::submitButton('Войти', ['class' => 'site-btn', 'name' => 'login-button']) ?>

	<?php ActiveForm::end(); ?>
</div>
