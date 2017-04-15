<?php

/* @var $this yii\web\View */
/* @var $models \common\models\CatalogManual[] */

use yii\helpers\Html;

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="manual-index">
    <h1><?= Html::encode($this->title) ?></h1>
	<?php if ($models): ?>
		<div class="manual-list">
			<?php foreach ($models as $model): ?>
				<div class="manual-item">
					<a href="/manual/<?php echo $model->link;?>" title="<?php echo $model->title; ?>">
						<div class="title"><?php echo $model->title; ?></div>
						<?php $image = $model->getImagePath(); ?>
						<?php echo Html::tag('div', '', [
							'class' => 'image',
							'style' => $image ? "background-image:url('" . $image . "')" : null,
						]); ?>
						<div class="year"><?php echo $model->year; ?></div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
        <p>Не найдено.</p>
	<?php endif; ?>
</div>
