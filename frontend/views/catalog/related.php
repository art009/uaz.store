<?php

use common\models\ManualProduct;
use frontend\widgets\ProductItem;
use yii\bootstrap\Html;

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
        <div id="related-products" class="related-product-view grid-view">
            <table class="table table-striped">
                <?php
                /**
                 * @var $relatedProduct ManualProduct
                 */
                ?>
                <tr>
                    <th>Номер на чертеже</th>
                    <th>Артикул завода</th>
                    <th>Название</th>
                </tr>
                <?php foreach ($product->relatedProducts as $i => $relatedProduct): ?>
                    <tr>
                        <td><?= $relatedProduct->number ?></td>
                        <td><?= $relatedProduct->code ?></td>
                        <td><?= $relatedProduct->title ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif ?>
</div>