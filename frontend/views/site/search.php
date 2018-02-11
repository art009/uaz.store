<?php

use frontend\widgets\ProductItem;

/* @var $this \yii\web\View */
/* @var $products \common\models\CatalogProduct[] */
/* @var $query string */
/* @var $emptyString string */

$this->title = 'Результаты поиска';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-view">
	<h1><?php echo $this->title ?></h1>

	<div class="row">
		<?php echo \frontend\widgets\SearchForm::widget(['query' => $query]); ?>
	</div>

	<div class="category-products-list">
		<p class="m-search-form__cont">Найдено товаров: <?php echo count($products); ?></p>
		<?php if ($products): ?>
			<?php foreach ($products as $product): ?>
				<?php echo ProductItem::widget(['product' => $product]); ?>
			<?php endforeach; ?>
		<?php else: ?>
			<?php echo $emptyString; ?>
		<?php endif; ?>
	</div>
</div>


