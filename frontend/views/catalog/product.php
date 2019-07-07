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
<div class="category-product" style="margin-left: 10px; margin-right: 10px">
    <h1><?php echo $this->title; ?></h1>
    <div style="display: flex">
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
            <?php if (sizeof($product->manuals) > 0): ?>
                <div class="manual-product-view" style="width: 66%">
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
                                    <td>
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
    </div>
    <div>
        <?php if (sizeof($product->similarProducts) > 0): ?>
            <h2 style="margin-top: 0">Аналогичные товары</h2>
            <div id="similar-products" class="manual-product-view grid-view">
                <table class="table table-striped table-bordered">
                    <?php
                    /**
                     * @var $similarProduct \common\models\CatalogProduct
                     */
                    ?>
                    <?php $counterSimilar = 0; ?>
                    <?php foreach ($product->similarProducts as $similarProduct): ?>
                        <?php if (sizeof($similarProduct->categories) == 0) {
                            continue;
                        } ?>
                        <?php $counterSimilar++;
                        if ($counterSimilar > 5) {
                            continue;
                        } ?>
                        <tr class="manual-product-row">
                            <td style="vertical-align: middle; text-align: center">
                                <?php if ($similarProduct->images): ?>
                                    <?php $productImage = $similarProduct->images[0] ?>
                                    <div class="product-image">
                                        <a href="<?php echo $similarProduct->getImagePath(false); ?>">
                                            <img src="<?php echo $similarProduct->getImagePath(); ?>" style="width: 285px;" alt="<?php echo $similarProduct->title; ?>"/>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <img src="/img/empty-s.png" alt="<?php echo $similarProduct->title; ?>">
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle; width: 40%">
                                <?php echo \yii\helpers\Html::a($similarProduct->title, [
                                    'catalog/product',
                                    'id' => $similarProduct->id,
                                    'categoryId' => $similarProduct->categories[0]->id
                                ], ['class' => 'open-catalog', 'target' => '_blank']); ?>
                            </td>
                            <td>
                                <?php echo $similarProduct->shop_code; ?>
                            </td>
                            <td>
                                <span><?php echo $similarProduct->price; ?></span> руб
                            </td>
                            <td style="width: 15%; text-align: center">
                                <div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
                                    Добавить в корзину
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if ($counterSimilar > 5): ?>
                    <?php echo Html::a('Все аналогичные товары', ['/catalog/similar', 'id' => $product->id],
                        ['class' => 'site-btn']) ?>
                <?php endif; ?>
            </div>
        <?php endif ?>
    </div>
</div>