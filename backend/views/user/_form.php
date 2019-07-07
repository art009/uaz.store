<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use backend\models\User;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS

    function checkVisibility() {
        var role = $('#user-role').val(),
            legal = $('#user-legal').val();
        
        if (legal == 2) { legal = 1}; //ИП тоже юр.лицо
        
        $.each($('[data-role]'), function(i, element) {
            if ($(element).data('role') == role) {
                $(element).show();
            } else {
                $(element).hide();
            }
        });
        if (role == 0) {
            $.each($('[data-legal]'), function(i, element) {
                if ($(element).data('legal') == legal) {
                    $(element).show();
                } else {
                    $(element).hide();
                }
            });
        }
    }
    
    function checkInn() {
      var legal = $('#user-legal').val();
      $('.inn-1').closest('div').hide();
      $('.inn-2').closest('div').hide();
      if (legal > 0) {
        $('.inn-'+legal).closest('div').show();
      }
    }
    
    $(document)
        .on('change', '#user-role, #user-legal', checkVisibility)
        .on('change', '#user-legal', checkInn)
        .on('input', '#user-inn', function(){
            $(this).val(this.value.match(/[0-9]*/));
        });
    
    checkVisibility();
    checkInn();
JS
    , yii\web\View::POS_READY);

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->errorSummary($model);
    ?>

    <?= $form->field($model, 'email')->textInput(['autocomplete' => 'new-email']) ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
        'mask' => '+7(999)999-99-99',
        'options' => [
            'class' => 'form-control tel_input',
        ],
        'clientOptions' => [
            'clearIncomplete' => false
        ]
    ]); ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
    <?php endif; ?>

    <?php if ($model->role != User::ROLE_ADMIN): ?>

        <?php echo $form->field($model, 'role')->dropDownList(User::getFormRoleList()) ?>
        <?php echo $form->field($model, 'legal', ['options' => ['data-role' => 0]])->dropDownList(User::$legalList) ?>

    <?php endif; ?>

    <?php echo $form->field($model, 'name') ?>

    <?php echo $form->field($model, 'representive_name',
        ['options' => ['data-role' => 0, 'data-legal' => 1]])->textInput(['class' => 'form-control business']) ?>
    <?php echo $form->field($model, 'representive_position',
        ['options' => ['data-role' => 0, 'data-legal' => 1]])->textInput(['class' => 'form-control business']) ?>
    <?php echo $form->field($model, 'bank_name',
        ['options' => ['data-role' => 0, 'data-legal' => 1]])->textInput(['class' => 'form-control business']) ?>
    <?php echo $form->field($model, 'bik',
        ['options' => ['data-role' => 0, 'data-legal' => 1]])->textInput(['class' => 'form-control business']) ?>
    <?php echo $form->field($model, 'account_number',
        ['options' => ['data-role' => 0, 'data-legal' => 1]])->textInput(['class' => 'form-control business']) ?>

    <div class="form-group field-user-image">
        <?php if ($model->photo): ?>
            <?= Html::activeLabel($model, 'photo'); ?>
            <br/>
            <?= Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->photo); ?>
            <br/>
        <?php endif; ?>
        <?= Html::label($model->getAttributeLabel('imageFile')); ?>
        <?= $form->field($model, 'imageFile', ['template' => '{input}{error}'])->fileInput(['accept' => 'image/*']) ?>
    </div>

    <?php if ($model->role != User::ROLE_ADMIN): ?>

        <?= $form->field($model, 'passport_series',
            ['options' => ['data-role' => 0, 'data-legal' => 0]])->widget(MaskedInput::className(), [
            'mask' => '9999',
            'options' => [
                'class' => 'form-control',
                'placeholder' => '4 цифры',
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ]
        ]); ?>
        <?= $form->field($model, 'passport_number',
            ['options' => ['data-role' => 0, 'data-legal' => 0]])->widget(MaskedInput::className(), [
            'mask' => '999999',
            'options' => [
                'class' => 'form-control',
                'placeholder' => '6 цифр',
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ]
        ]); ?>
        <?php echo $form->field($model, 'inn')->hiddenInput()->label(false); ?>
        <?php echo $form->field($model, 'inn1', ['options' => ['data-inn' => 1]])->widget(MaskedInput::className(), [
            'mask' => '999 999 9999',
            'options' => [
                'class' => 'form-control inn-1',
                'placeholder' => '10 цифр',
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ]
        ]); ?>
        <?php echo $form->field($model, 'inn2', ['options' => ['data-inn' => 1]])->widget(MaskedInput::className(), [
            'mask' => '999 999 999 999',
            'options' => [
                'class' => 'form-control inn-2',
                'placeholder' => '12 цифр',
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ]
        ]); ?>
        <?= $form->field($model, 'kpp',
            ['options' => ['data-role' => 0, 'data-legal' => 1]])->widget(MaskedInput::className(), [
            'mask' => '999999999',
            'options' => [
                'class' => 'form-control',
                'placeholder' => '9 цифр',
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ]
        ]); ?>
        <?= $form->field($model, 'postcode', ['options' => ['data-role' => 0]])->widget(MaskedInput::className(), [
            'mask' => '999999',
            'options' => [
                'class' => 'form-control',
                'placeholder' => '6 цифр',
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ]
        ]); ?>
        <?php echo $form->field($model, 'address', ['options' => ['data-role' => 0]])->textInput([
            'placeholder' => 'Город, улица, дом, корпус, квартира'
        ]) ?>
        <?php echo $form->field($model, 'fax', ['options' => ['data-role' => 0]]) ?>
        <?php echo $form->field($model, 'offer_accepted', ['options' => ['data-role' => 0]])->checkbox() ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
