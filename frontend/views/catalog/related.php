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
    <?php if (sizeof($product->relatedProducts) > 0): ?>
        <h2 style="margin-top: 0">Сопутствующие товары</h2>
        <div id="similar-products" class="manual-product-view grid-view">
            <?php foreach ($product->relatedProducts as $relatedProduct): ?>
                <?php echo ProductItem::widget(['product' => $relatedProduct]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
</div>