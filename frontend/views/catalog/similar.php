<?php

use frontend\widgets\ProductItem;

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory */
/* @var $product \common\models\CatalogProduct */

$this->title = $product->title;
$this->params['breadcrumbs'] = $product->createBreadcrumbs();

?>
<div class="category-product">
    <h1><?php echo $this->title; ?></h1>
    <?php if (sizeof($product->similarProducts) > 0): ?>
        <h2 style="margin-top: 0">Аналогичные товары</h2>
        <div id="similar-products" class="manual-product-view grid-view">
            <?php foreach ($product->similarProducts as $similarProduct): ?>
                <?php echo ProductItem::widget(['product' => $similarProduct]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
</div>