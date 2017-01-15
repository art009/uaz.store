<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {

    $this->registerJs(<<<JS

    $('#page-title').syncTranslit({destination: 'page-link'});

JS
        , yii\web\View::POS_READY);

}
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(TinyMce::className());?>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'hide')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
