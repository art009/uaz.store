<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogProduct */

$this->title = 'Редактирование товара: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории товаров', 'url' => ['/catalog']];
if ($model->category) {
    if ($parentsList = $model->category->getParentsList(true)) {
        foreach ($parentsList as $id => $title) {
            $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['catalog-category/index', 'id' => $id]];
        }
    }
    $this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['catalog-category/index', 'id' => $model->category->id]];
}
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
