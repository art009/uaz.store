<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogManualPage */
/* @var $manual backend\models\CatalogManual|null */
/* @var $category backend\models\CatalogCategory|null */

$this->title = 'Редактирование страницы справочника ' . $manual->title . ': ' . $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/catalog-manual/index']];
$this->params['breadcrumbs'][] = ['label' => 'Справочник: ' . $manual->title , 'url' => ['/catalog-manual/view', 'id' => $manual->id]];
$this->params['breadcrumbs'][] = [
    'label' => 'Страница справочника ' . $manual->title . ': ' . $category->title,
    'url' => ['view', 'cid' => $model->category_id, 'mid' => $model->manual_id]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-manual-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
