<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Manual */

$this->title = 'Добавление справочника';
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-manual-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
