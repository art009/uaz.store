<?php

use common\models\ManualProduct;
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use frontend\widgets\ProductItem;

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory */
/* @var $product \common\models\CatalogProduct */

$this->title = $product->title;
$this->params['breadcrumbs'] = $product->createBreadcrumbs();

?>
<div class="category-product" style="margin-left: 10px; margin-right: 10px">
    <h1><?php echo $this->title; ?></h1>
    <div>
        <div class="100%">
            <div style="width: 60%">
                <div class="product-content">
                    <div class="product-images" style="vertical-align: center; text-align: center">
                        <?php if ($product->images): ?>
                            <?php foreach ($product->images as $productImage): ?>
                                <div class="product-image">
                                    <a href="<?php echo $productImage->getImagePath(false); ?>" data-fancybox="1"
                                       rel="group">
                                        <img src="<?php echo $productImage->getImagePath(); ?>"
                                             alt="<?php echo $product->title; ?>"/>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <img src="/img/empty-s.png" alt="<?php echo $product->title; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        Код на сайте: <b><?php echo $product->getCode(); ?></b>
                        <br/>
                        <?php if ($product->shop_code): ?>
                            Артикул: <b><?php echo $product->shop_code; ?></b>
                            <br/>
                        <?php endif; ?>
                        Цена: <b><span><?php echo $product->price; ?></span> руб</b>
                        <br/>
                        <div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
                            Добавить в корзину
                        </div>
                    </div>
                    <?php if ($product->oversize): ?>
                        <?php echo Html::icon('scale', [
                            'class' => 'bulky-product',
                            'data-tooltip' => 'tooltip',
                            'data-trigger' => 'hover',
                            'data-placement' => 'right',
                            'title' => 'Крупногабаритный товар',
                        ]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (sizeof($product->manuals) > 0): ?>
            <div class="manual-product-view" style="width: 100%">
                <h2>Чертежи</h2>
                <div id="w0" class="grid-view">
                    <table class="table table-striped table-bordered">
                        <?php
                        /**
                         * @var $manualProduct ManualProduct
                         */
                        ?>
                        <?php foreach ($product->manuals as $manualProduct): ?>
                            <tr class="manual-product-row">
                                <td><?php $breadcrumbs = $manualProduct->manualCategory->createBreadcrumbs();
                                    unset($breadcrumbs[0]);
                                    echo Breadcrumbs::widget([
                                        'links' => $breadcrumbs,
                                        'homeLink' => false
                                    ]); ?></td>
                                <td style="text-align: center">
                                    <?= Html::a('<div class="site-btn open-catalog">Перейти</div>', [
                                        'manual/image',
                                        'id' => $manualProduct->manualCategory->manual_id,
                                        'categoryId' => $manualProduct->manualCategory->id
                                    ], ['target' => '_blank']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div>
        <?php if (sizeof($product->similarProducts) > 0): ?>
            <h2 style="margin-top: 0">Аналогичные товары</h2>
            <div id="similar-products" class="similar-product-view grid-view">
                <?php $similarProductsMaxItems = 5; $shown = [];
                foreach ($product->similarProducts as $i => $similarProduct): ?>
                    <?php if ($i >= $similarProductsMaxItems) {
                        continue;
                    } ?>
                    <?php $shown[] = $similarProduct->id; ?>
                    <?php echo ProductItem::widget(['product' => $similarProduct]); ?>
                <?php endforeach; ?>
                <?php if (sizeof($product->similarProducts) > $similarProductsMaxItems): ?>
                    <?php echo Html::a('Все аналогичные товары', ['/catalog/similar', 'id' => $product->id],
                        ['class' => 'similar-product-btn site-btn']) ?>
                <?php endif; ?>
            </div>
        <?php endif ?>
        <?php if (sizeof($product->relatedProducts) > 0): ?>
            <h2>Сопутствующие товары</h2>
            <div id="related-products" class="related-product-view grid-view">
                <?php $relatedProductsMaxItems = 20; $counter = 0;
                foreach ($product->relatedProducts as $i => $relatedProduct): ?>
                    <?php if ($counter >= $relatedProductsMaxItems) {
                        continue;
                    } ?>
                    <?php if (in_array($relatedProduct->id, $shown)) {
                        continue;
                    } ?>
                    <?php echo ProductItem::widget(['product' => $relatedProduct]); ?>
                    <?php $counter++ ?>
                <?php endforeach; ?>
                <?php if (sizeof($product->relatedProducts) > $relatedProductsMaxItems): ?>
                    <?php echo Html::a('Все сопутствующие товары', ['/catalog/related', 'id' => $product->id],
                        ['class' => 'related-product-btn site-btn']) ?>
                <?php endif; ?>
            </div>
        <?php endif ?>
    </div>
</div>