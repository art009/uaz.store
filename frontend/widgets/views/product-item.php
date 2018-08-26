<?php

use yii\bootstrap\Html;

/* @var $product \common\models\CatalogProduct */

$link = $product->getFullLink();
$title = $product->title;
$image = Html::img($product->getImagePath(), ['alt' => $title]);
?>
<div class="product-item">
	<div class="title">
		<?php echo $link ? Html::a($title, $link) : $title; ?>
	</div>
	<div class="image">
		<?php if ($product->oversize): ?>
			<?php echo Html::icon('scale', [
				'class' => 'bulky-product',
				'data-tooltip' => 'tooltip',
				'data-trigger' => 'hover',
				'data-placement' => 'right',
				'title' => 'Крупногабаритный товар',
			]); ?>
		<?php endif; ?>
		<?php echo $link ? Html::a($image, $link) : $image; ?>
	</div>
	<div class="code">Код <?php echo $product->getCode(); ?></div>
	<div class="price">
		Цена: <b><?php echo $product->price; ?> руб</b>
	</div>
	<div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
		Добавить в корзину
	</div>
</div>
