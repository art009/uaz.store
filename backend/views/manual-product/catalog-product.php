<?php

use common\components\AppHelper;
use common\models\ManualCategory;
use common\models\ManualProduct;
use yii\bootstrap\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $productSearch backend\models\CatalogProductSearch */
/* @var $productProvider yii\data\ActiveDataProvider */
/* @var $manualProduct ManualProduct */
/* @var $manualCategory ManualCategory */

$this->title = $manualProduct->title;
$this->params['breadcrumbs'] = $manualCategory->createBackendBreadcrumbs(false);
$this->params['breadcrumbs'][] = [
	'label' => $manualCategory->title,
	'url' => ['/manual-category/view', 'id' => $manualCategory->id]
];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="manual-category-index">
    <h1>Добавление товаров к позиции: <?= Html::encode($this->title) ?></h1>
	<h2>Добавленные товары</h2>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
				'attribute' => 'id',
				'options' => [
					'width' => '40px;'
				]
			],
			[
				'attribute' => 'title',
			],
			[
				'attribute' => 'image',
				'format' => 'raw',
				'value' => function ($model) {
					/* @var $model \backend\models\CatalogProduct */
					return $model->image ? Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->image) : null;
				},
				'filter' => AppHelper::$yesNoList,
			],
			'shop_code',
			'external_id',
			[
				'class' => 'yii\grid\ActionColumn',
				'options' => [
					'width' => '70px;'
				],
				'template' => '{delete}',
				'buttons' => [
					'delete' => function ($url, $model, $key) use ($manualProduct) {
						return Html::a(Html::icon('trash'), [
							'/manual-product/catalog-product-revoke',
							'id' => $manualProduct->id,
							'catalogProductId' => $model->id,
						], [
							'title' => 'Удалить',
							'aria-label' => 'Удалить',
						]);
					},
				]
			],
		],
	]); ?>
	<h3>Поиск товаров</h3>
	<?= GridView::widget([
		'dataProvider' => $productProvider,
		'filterModel' => $productSearch,
		'columns' => [
			[
				'attribute' => 'id',
				'options' => [
					'width' => '40px;'
				]
			],
			[
				'attribute' => 'title',
			],
			[
				'attribute' => 'image',
				'format' => 'raw',
				'value' => function ($model) {
					/* @var $model \backend\models\CatalogProduct */
					return $model->image ? Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->image) : null;
				},
				'filter' => AppHelper::$yesNoList,
			],
			'shop_code',
			'external_id',
			[
				'class' => 'yii\grid\ActionColumn',
				'options' => [
					'width' => '70px;'
				],
				'template' => '{apply}',
				'buttons' => [
					'apply' => function ($url, $model, $key) use ($manualProduct) {
						return Html::a('Добавить', [
							'/manual-product/catalog-product-apply',
							'id' => $manualProduct->id,
							'catalogProductId' => $model->id,
						]);
					},
				]
			],
		],
	]); ?>
</div>
