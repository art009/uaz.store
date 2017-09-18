<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Manual */
/* @var $categories \common\models\ManualCategory[] */

use yii\helpers\Html;
use frontend\widgets\ManualCategoryTreeWidget;

$this->title = 'Справочник ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/manual']];
$this->params['breadcrumbs'][] = $model->title;

?>
<div class="manual-view">
    <h1><?= Html::encode($model->title) ?></h1>
	<?php echo ManualCategoryTreeWidget::widget([
		'baseLink' => '/manual/' . $model->link . '/',
		'manualId' => $model->id,
		'categoryId' => null,
		'toggleableParent' => false,
	]); ?>
	<div class="manual-view-content manual-list">
		<?php if ($categories): ?>
			<?php foreach ($categories as $category): ?>
				<div class="manual-item">
					<a href="<?php echo $category->getFullLink(); ?>" title="<?php echo $category->title; ?>">
						<div class="title"><?php echo $category->title; ?></div>
						<?php $image = $category->getClosestImage(); ?>
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
