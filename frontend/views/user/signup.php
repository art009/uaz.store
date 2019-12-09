<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use common\models\User;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'user-signup-form',
        'options' => ['autocomplete' => 'off'],
        'enableClientValidation' => false,
    ]); ?>
    <div class="form-header tabs tabs-right">
        <div class="col-xs-6 text-center"><a href="/login">Вход</a></div>
        <div class="col-xs-6 text-center">Регистрация</div>
    </div>
    <div class="clearfix"></div>

    <!-- >>> Avoid Chrome autofill >>> -->
    <?php echo Html::activeTextInput($model, 'name', ['class' => 'hidden']); ?>
    <?php echo Html::activeTextInput($model, 'email', ['class' => 'hidden']); ?>
    <?php echo Html::activePasswordInput($model, 'password', ['class' => 'hidden']); ?>
    <!-- <<< Avoid Chrome autofill <<< -->

    <?php echo $form
        ->field($model, 'legal')
        ->inline()
        ->radioList(User::$legalList, [
            'item' => function ($index, $label, $name, $checked, $value) {

                $html = Html::beginTag('span', ['class' => 'radio-inline']);
                $html .= Html::radio($name, $checked,
                    ['value' => $value, 'id' => 'radio_' . $index, 'checked' => $checked]);
                $html .= Html::label($label, 'radio_' . $index);
                $html .= Html::endTag('span');

                return $html;
            }
        ])
        ->label(false);
    ?>

    <?= $form->field($model, 'email', [
        'template' => '{input}{error}{hint}'
    ])->textInput([
        'autofocus' => true,
        'placeholder' => $model->getAttributeLabel('email'),
    ]) ?>

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
    ]); ?>

    <?= $form->field($model, 'name', [
        'template' => '{input}{error}{hint}'
    ])->textInput([
        'placeholder' => $model->getAttributeLabel('name'),
    ]) ?>

    <?= $form->field($model, 'password', [
        'template' => '{input}{error}{hint}'
    ])->passwordInput([
        'placeholder' => $model->getAttributeLabel('password')
    ]) ?>

    <?= $form->field($model, 'offer_accepted', [
        'template' => '{input}{error}{hint}'
    ])->checkbox([
        'template' => '{input} {label}{error}',
    ])->label('Согласие с условиями <a href="/offer" target="_blank">оферты</a>') ?>

    <div class="form-group">
        <p class="help-block" style="color: whitesmoke">Для регистрации нужно ввести телефон или email</p>
    </div>

    <div class="form-group">
        <p class="help-block" style="color: whitesmoke">Поля отмеченные * обязательны</p>
    </div>

    <?= Html::submitButton('Зарегистрироваться', ['class' => 'site-btn', 'name' => 'signup-button']) ?>

    <?php ActiveForm::end(); ?>
</div>