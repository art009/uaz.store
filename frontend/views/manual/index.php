<?php

/* @var $this yii\web\View */
/* @var $models \common\models\CatalogManual[] */

use yii\helpers\Html;
use common\components\AppHelper;
use common\models\CatalogManual;

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = $this->title;

$imgPath = AppHelper::uploadsPath() . '/' . CatalogManual::FOLDER_MEDIUM . '/';
?>
<div class="manual-index">
    <h1><?= Html::encode($this->title) ?></h1>
	<?php if ($models): ?>
		<div class="manual-list">
			<?php foreach ($models as $model): ?>
				<div class="manual-item">
					<a href="#" title="<?php echo $model->title; ?>">
						<div class="title"><?php echo $model->title; ?></div>
						<div class="image" style="background-image: url('<?php echo $imgPath . $model->image;?>')"></div>
						<div class="year"><?php echo $model->year; ?></div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
        <p>Не найдено.</p>
	<?php endif; ?>
</div>
