<?php

/* @var $this yii\web\View */
/* @var $categories \common\models\CatalogCategory[] */
/* @var $id int */

use yii\helpers\Html;
use frontend\widgets\CategoryTreeWidget;

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>
	<?php echo CategoryTreeWidget::widget([
		'baseLink' => '/catalog/',
		'categoryId' => $id,
		'toggleableParent' => false,
	]); ?>
	<div class="category-view-content category-list">
		<?php if ($categories): ?>
			<?php foreach ($categories as $category): ?>
				<div class="category-item">
					<a href="/catalog<?php echo $category->getFullLink(); ?>" title="<?php echo $category->title; ?>">
						<div class="title"><?php echo $category->title; ?></div>
						<?php $image = $category->getImagePath(true); ?>
						<?php echo Html::tag('div', '', [
							'class' => 'image',
							'style' => $image ? "background-image:url('" . $image . "')" : null,
						]); ?>
					</a>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
