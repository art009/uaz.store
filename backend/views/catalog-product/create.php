<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CatalogProduct */
/* @var $category backend\models\CatalogCategory */

$this->title = 'Добавление товара';
$this->params['breadcrumbs'][] = ['label' => 'Категории товаров', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
