<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory */
/* @var $product \common\models\CatalogProduct */

$this->title = $product->title;
$this->params['breadcrumbs'] = $product->createBreadcrumbs();

?>
<div class="category-product">
	<h1><?php echo $this->title; ?></h1>
	<div class="product-content">
		<div class="product-images">
			<?php if ($product->images): ?>
				<?php foreach ($product->images as $productImage): ?>
					<div class="product-image">
						<a href="<?php echo $productImage->getImagePath(false); ?>" data-fancybox="1" rel="group">
							<img src="<?php echo $productImage->getImagePath(); ?>" alt="<?php echo $product->title; ?>"/>
						</a>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				Нет картинок
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
