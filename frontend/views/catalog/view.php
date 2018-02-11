<?php

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory */
/* @var $products \common\models\CatalogProduct[] */

use frontend\widgets\ProductItem;
use yii\helpers\Html;

$this->title = $category->title;
$this->params['breadcrumbs'] = $category->createBreadcrumbs();

?>
<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>

	<div class="category-products-list">
		<?php if ($products): ?>
			<?php foreach ($products as $product): ?>
				<?php echo ProductItem::widget(['product' => $product]); ?>
			<?php endforeach; ?>
		<?php else: ?>
			Товары не найдены.
		<?php endif; ?>
	</div>
</div>
