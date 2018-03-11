<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ManualCategory */

$this->title = 'Добавление категории';
$this->params['breadcrumbs'] = $model->createBackendBreadcrumbs(false);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
