<?php

/* @var $this yii\web\View */
/* @var $category \common\models\CatalogCategory|null */
/* @var $children \common\models\CatalogCategory[] */
/* @var $id int */

use frontend\widgets\CategoryTreeWidget;
use frontend\widgets\SeoTextWidget;
use yii\helpers\Html;

$this->title = $category ? $category->title : 'Каталог';
$this->params['breadcrumbs'] = $category ? $category->createBreadcrumbs() : [$this->title];
?>
<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>
	<?php echo CategoryTreeWidget::widget([
		'baseLink' => '/catalog/',
		'categoryId' => $id,
		'toggleableParent' => false,
	]); ?>
	<div class="category-view-content category-list">
		<?php if ($children): ?>
			<?php foreach ($children as $child): ?>
				<div class="category-item">
					<a href="/catalog<?php echo $child->getFullLink(); ?>" title="<?php echo $child->title; ?>">
						<div class="title"><?php echo $child->title; ?></div>
						<?php $image = $child->getImagePath(true); ?>
						<?php echo Html::tag('div', '', [
							'class' => 'image',
							'style' => $image ? "background-image:url('" . $image . "')" : null,
						]); ?>
					</a>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div><?php echo SeoTextWidget::widget(); ?></div>
	</div>
</div>
