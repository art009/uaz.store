<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = 'Редактирование пункта меню: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
