<?php
/* @var $this yii\web\View */
/* @var $items \common\classes\TreeItem[] */
?>
<?php if ($items): ?>
	<div class="category-tree">
		<ul>
		<?php foreach ($items as $item): ?>
		<?php echo $this->render('_category-tree-item', ['item' => $item]);?>
		<?php endforeach; ?>
		</ul>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>
