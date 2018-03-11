<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ManualProduct */
/* @var $category common\models\ManualCategory */

$this->title = 'Добавление позиции';

$this->params['breadcrumbs'] = $category->createBackendBreadcrumbs(false);
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['/manual-category/view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
