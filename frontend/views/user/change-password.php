<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\ChangePasswordForm */

$this->title = 'Смена пароля';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['/user/edit']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-profile">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
        'enableClientValidation' => false,
    ]); ?>

    <div class="clearfix"></div>

    <?php echo $form->field($model, 'password', [
        'template' => '{input}{error}{hint}'
    ])->passwordInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('password')]); ?>

    <?php echo $form->field($model, 'password_confirm', [
        'template' => '{input}{error}{hint}'
    ])->passwordInput(['placeholder' => $model->getAttributeLabel('password_confirm')]); ?>

    <?php echo Html::submitButton('Сохранить', ['class' => 'site-btn', 'name' => 'login-button']) ?>

    <?php ActiveForm::end(); ?>

</div>
