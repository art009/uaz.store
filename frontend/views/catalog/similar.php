<?php

use common\models\ManualProduct;
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;

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
            <table class="table table-striped table-bordered">
                <?php
                /**
                 * @var $similarProduct \common\models\CatalogProduct
                 */
                ?>
                <?php foreach ($product->similarProducts as $similarProduct): ?>
                    <?php if (sizeof($similarProduct->categories) == 0) {
                        continue;
                    } ?>
                    <tr class="manual-product-row">
                        <td style="vertical-align: middle; text-align: center">
                            <?php if ($similarProduct->images): ?>
                                <?php $productImage = $similarProduct->images[0] ?>
                                <div class="product-image">
                                    <a href="<?php echo $similarProduct->getImagePath(false); ?>">
                                        <img src="<?php echo $similarProduct->getImagePath(); ?>"
                                             alt="<?php echo $similarProduct->title; ?>"/>
                                    </a>
                                </div>
                            <?php else: ?>
                                <img src="/img/empty-s.png" alt="<?php echo $similarProduct->title; ?>">
                            <?php endif; ?>
                        </td>
                        <td style="vertical-align: middle; width: 30%">
                            <?php echo \yii\helpers\Html::a($similarProduct->title, [
                                'catalog/product',
                                'id' => $similarProduct->id,
                                'categoryId' => $similarProduct->categories[0]->id
                            ], ['class' => 'open-catalog', 'target' => '_blank']) ?>
                        </td>
                        <td>
                            <?php echo $similarProduct->shop_code; ?>
                        </td>
                        <td>
                            <span><?php echo $similarProduct->price; ?></span> руб
                        </td>
                        <td style="width: 24%; text-align: center">
                            <div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
                                Добавить в корзину
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif ?>
</div>