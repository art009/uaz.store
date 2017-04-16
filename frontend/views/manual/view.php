<?php

/* @var $this yii\web\View */
/* @var $model \common\models\CatalogManual */
/* @var $category \common\models\CatalogCategory */

use yii\helpers\Html;
use frontend\widgets\CategoryTreeWidget;

$this->title = 'Справочник';
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/manual']];
if ($category) {
	$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['/manual/' . $model->link]];
	$this->params['breadcrumbs'][] = $category->title;
} else {
	$this->params['breadcrumbs'][] = $model->title;
}

?>
<div class="manual-view">
    <h1><?= Html::encode($model->title) ?></h1>
	<?php echo CategoryTreeWidget::widget([
		'view' => CategoryTreeWidget::VIEW_MANUAL,
		'baseLink' => '/manual/' . $model->link . '/',
		'categoryId' => $category ? $category->id : null,
	]); ?>
	<p>
		Пустой справочник
	</p>
</div>
