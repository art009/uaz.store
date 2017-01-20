<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogProduct */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Catalog Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category_id',
            'title',
            'link',
            'image',
            'meta_keywords:ntext',
            'meta_description:ntext',
            'price',
            'price_to',
            'price_old',
            'shop_title',
            'provider_title',
            'shop_code',
            'provider_code',
            'description:ntext',
            'hide',
            'on_main',
            'provider',
            'manufacturer',
            'cart_counter',
            'length',
            'width',
            'height',
            'weight',
            'unit',
            'rest',
            'external_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
