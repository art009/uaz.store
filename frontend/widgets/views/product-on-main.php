<?php

/* @var $products \common\models\CatalogProduct[] */

use frontend\widgets\ProductItem;

?>
<?php if ($products): ?>
<div class="products-list">
	<?php foreach ($products as $product): ?>
		<?php echo ProductItem::widget(['product' => $product]); ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="clearfix"></div>

