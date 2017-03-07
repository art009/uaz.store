<?php
/* @var $products \common\models\CatalogProduct[] */
?>
<?php if ($products): ?>
<div class="products-list">
	<?php foreach ($products as $product): ?>
	<div class="product-item">
		<div class="title"><?php echo $product->title; ?></div>
		<div class="image">
			<img src="/img/product.jpg" alt="<?php echo $product->title; ?>"/>
		</div>
		<div class="code">Артикул <?php echo str_pad($product->id, 4, '0', STR_PAD_LEFT); ?></div>
		<div class="price">
			Цена: <b><?php echo $product->price; ?> руб</b>
		</div>
		<div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
			Добавить в корзину
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="clearfix"></div>

