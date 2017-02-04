<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS

    $('#catalogproduct-title').syncTranslit({destination: 'catalogproduct-link'});

JS
	, yii\web\View::POS_READY);

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        echo $form->errorSummary($model);
    ?>

	<?= $form->field($model, 'email')->textInput(['autofocus' => true, 'autocomplete' => 'new-email']) ?>

	<?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
		'mask' => '+7(999)999-99-99',
		'options' => [
			'class' => 'form-control tel_input',
		],
		'clientOptions' => [
			'clearIncomplete' => false
		]
    ]);?>

    <?php if ($model->isNewRecord): ?>
    <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
    <?php endif; ?>

    <?php echo $form->field($model, 'role')->dropDownList(User::getFormRoleList()) ?>

    <?php echo $form->field($model, 'legal')->dropDownList(User::$legalList) ?>

    <?php echo $form->field($model, 'name') ?>

    <?php echo $form->field($model, 'passport_series') ?>

    <?php echo $form->field($model, 'passport_number') ?>

    <?php echo $form->field($model, 'inn') ?>

    <?php echo $form->field($model, 'kpp') ?>

    <?php echo $form->field($model, 'postcode') ?>

    <?php echo $form->field($model, 'address') ?>

    <?php echo $form->field($model, 'fax') ?>

    <?php echo $form->field($model, 'offer_accepted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
