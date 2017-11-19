<?php

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
			Код: <?php echo $product->getCode(); ?>
			<br/>
			Цена: <b><?php echo $product->price; ?> руб</b>
			<br/>
			<div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
				Добавить в корзину
			</div>
		</div>
	</div>
</div>
