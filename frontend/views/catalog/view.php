<?php

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory */

use yii\helpers\Html;
use frontend\widgets\ProductItem;

$this->title = $category->title;
$this->params['breadcrumbs'] = $category->createBreadcrumbs();

$products = $category->getProductModels('title, ISNULL(image)');
?>
<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
	    <?php echo \frontend\widgets\SearchForm::widget(); ?>
    </div>

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
