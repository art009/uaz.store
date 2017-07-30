<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Manual */
/* @var $category \common\models\ManualCategory */

use yii\helpers\Html;
use frontend\widgets\ManualCategoryTreeWidget;

$this->title = 'Справочник';
$this->params['breadcrumbs'] = $category->createBreadcrumbs();

?>
<div class="manual-view">
    <h1><?= Html::encode($category ? $category->title : $model->title) ?></h1>
		<?php $image = $category ? $category->getImagePath() : null; ?>
		<?php if ($image): ?>
		<div class="manual-page-image">
			<div class="manual-page-container">
				<?php echo Html::img($image); ?>
				<?php if ($category->manualProducts): ?>
					<?php foreach ($category->manualProducts as $manualProduct): ?>
						<?php
						$styles = Html::cssStyleFromArray([
							'left' => $manualProduct->left . 'px',
							'top' => $manualProduct->top . 'px',
							'width' => $manualProduct->width . 'px',
							'height' => $manualProduct->height . 'px',
						]);
						?>
						<div class="image-product" style="<?php echo $styles; ?>"><?php echo $manualProduct->number; ?></div>
						<?php $positions = $manualProduct->getPositionsArray(); ?>
						<?php if ($positions): ?>
							<?php foreach ($positions as $position): ?>
								<?php
								$styles = Html::cssStyleFromArray([
									'left' => $position['left'] . 'px',
									'top' => $position['top'] . 'px',
									'width' => $position['width'] . 'px',
									'height' => $position['height'] . 'px',
								]);
								?>
								<div class="image-product" style="<?php echo $styles; ?>"><?php echo $manualProduct->number; ?></div>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>
			<div class="manual-page-tools">
				<span class="tool-zoom-label">Масштаб <b>100</b>%</span>
				<span class="tool-zoom-minus">-</span>
				<span class="tool-zoom-original">100%</span>
				<span class="tool-zoom-plus">+</span>
			</div>
		</div>
		<?php endif; ?>
</div>
