<?php

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory */

use yii\helpers\Html;

$this->title = $category->title;
$this->params['breadcrumbs'] = $category->createBreadcrumbs();

$products = $category->products;
?>
<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>
	<div class="category-products-list">
		<?php if ($products): ?>
			<?php foreach ($products as $product): ?>
				<div class="product-item">
					<div class="title"><?php echo $product->title; ?></div>
					<div class="image">
						<img src="<?php echo $product->getImagePath(); ?>" alt="<?php echo $product->title; ?>"/>
					</div>
					<div class="code">Код <?php echo $product->getCode(); ?></div>
					<div class="price">
						Цена: <b><?php echo $product->price; ?> руб</b>
					</div>
					<div class="site-btn add-cart-product" data-id="<?php echo $product->id; ?>">
						Добавить в корзину
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			Товары не найдены.
		<?php endif; ?>
	</div>
</div>
