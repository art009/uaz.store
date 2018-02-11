<?php

use frontend\widgets\ProductItem;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $products \common\models\CatalogProduct[] */
/* @var $query string */
/* @var $text string */
?>
<div class="category-view">
	<div class="row">
		<div class="m-search-form__cont col-md-6 col-sm-12">
			<?php echo Html::beginForm('/search', 'get', ['id' => 'm-search-form']); ?>
				<div class="input-group">
					<?php echo Html::textInput('q', $query, ['class' => 'form-control', 'placeholder' => 'Введите название или артикул']); ?>
					<div class="input-group-btn">
						<?php echo Html::button(Html::icon('search'), ['class' => 'btn btn-default', 'type' => 'submit']); ?>
					</div>
				</div>
			<?php echo Html::endForm(); ?>
		</div>
	</div>
	<div class="category-products-list">
		<?php if ($products): ?>
			<p class="m-search-form__cont">Найдено товаров: <?php echo count($products); ?></p>
			<?php foreach ($products as $product): ?>
				<?php echo ProductItem::widget(['product' => $product]); ?>
			<?php endforeach; ?>
		<?php else: ?>
			<?php echo $text; ?>
		<?php endif; ?>
	</div>
</div>


