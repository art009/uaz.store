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
    <div class="form-header">
        <div class="col-xs-6 text-center">Регистрация</div>
        <div class="col-xs-6 text-center"><a href="/login">Вход</a></div>
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

    <?= $form->field($model, 'representive_name', [
        'template' => '{input}{error}{hint}',
        'inputOptions' => [
            'class' => 'form-control business'
        ]
    ])->textInput([
        'placeholder' => $model->getAttributeLabel('representive_name'),
    ]) ?>

    <?= $form->field($model, 'representive_position', [
        'template' => '{input}{error}{hint}',
        'inputOptions' => [
            'class' => 'form-control business'
        ]
    ])->textInput([
        'placeholder' => $model->getAttributeLabel('representive_position'),
    ]) ?>

    <?= $form->field($model, 'bank_name', [
        'template' => '{input}{error}{hint}',
        'inputOptions' => [
            'class' => 'form-control business'
        ]
    ])->textInput([
        'placeholder' => $model->getAttributeLabel('bank_name'),
    ]) ?>

    <?= $form->field($model, 'bik', [
        'template' => '{input}{error}{hint}',
        'inputOptions' => [
            'class' => 'form-control business'
        ]
    ])->textInput([
        'placeholder' => $model->getAttributeLabel('bik'),
    ]) ?>

    <?= $form->field($model, 'account_number', [
        'template' => '{input}{error}{hint}',
        'inputOptions' => [
            'class' => 'form-control business'
        ]
    ])->textInput([
        'placeholder' => $model->getAttributeLabel('account_number'),
    ]) ?>

    <?= $form->field($model, 'offer_accepted', [
        'template' => '{input}{error}{hint}'
    ])->checkbox([
        'template' => '{input} {label}{error}',
    ])->label('Согласие с условиями <a href="/offer" target="_blank">оферты</a>') ?>

    <?= Html::submitButton('Зарегистрироваться', ['class' => 'site-btn', 'name' => 'signup-button']) ?>

    <?php ActiveForm::end(); ?>
</div>