<?php

/* @var $product \common\models\CatalogProduct */

?>
<div class="product-item">
	<div class="title">
		<a href="<?php echo $product->getFullLink(); ?>"><?php echo $product->title; ?></a>
	</div>
	<div class="image">
		<a href="<?php echo $product->getFullLink(); ?>">
			<img src="<?php echo $product->getImagePath(); ?>" alt="<?php echo $product->title; ?>"/>
		</a>
	</div>
	<div class="code">Код <?php echo $product->getCode(); ?></div>
	<div class="price">
		Цена: <b><?php echo $product->price; ?> руб</b>
	</div>
	<div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
		Добавить в корзину
	</div>
</div>
