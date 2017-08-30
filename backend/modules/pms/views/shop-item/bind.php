<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ShopItem */
/* @var $provider \app\modules\pms\models\Provider */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $linkDataProvider \yii\data\ActiveDataProvider */
/* @var $searchQuery string */
/* @var $wordSearchQuery string */
/* @var $providerList array */

$this->title = $model->title . ' [ ' . $model->vendor_code . ' ] [ ' . $model->price . ' ]';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Товары магазина', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Связывание';

$shopItemId = $model->id;
$nextId = $model->getNextUnBoundId();
?>
<div class="shop-item-bind">

	<h1><?= Html::encode($this->title) ?></h1>
	<div class="row">
		<div class="col-xs-4">
			Установленные связи:
		</div>
		<div class="col-xs-5 text-right" style="margin-bottom: 4px;">
			<?php echo Html::a('Игнор', ['ignore', 'id' => $model->id], ['class' => 'btn btn-sm btn-default']) ?>
			<?php echo Html::a('Не нашел!', ['not-found', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
		</div>
		<div class="col-xs-3 text-right" style="margin-bottom: 4px;">
			<?php if ($nextId): ?>
			<?php echo Html::a('Следующий непривязанный', ['bind', 'id' => $nextId], ['class' => 'btn btn-sm btn-primary']) ?>
			<?php endif; ?>
		</div>
	</div>
    <?php echo GridView::widget([
        'dataProvider' => $linkDataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'shop-item-link-table',
	        'data-item-id' => $shopItemId
        ],
		'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'vendor_code',
			'title',
			'price',
			'unit',
			'manufacturer',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{quantity}{unlink}',
                'buttons' => [
					'quantity' => function ($url, $model) use ($shopItemId) {
						/* @var $model \app\modules\pms\models\ProviderItem */
						return Html::input('number', 'quantity', $model->getLinkQuantity($shopItemId), [
							'title' => 'Количество',
							'class' => 'form-control quantity',
							'step' => 1,
							'min' => 1,
						]);
					},
					'unlink' => function ($url) use ($shopItemId) {
						return Html::a(Html::icon('remove-sign'), $url . '&shopItemId=' . $shopItemId, ['title' => 'Отвязать', 'class' => 'btn-unlink-item']);
					},
                ],
	            'options' => [
		            'width' => '90px',
	            ],
            ],
        ],
    ]); ?>
	<?php Pjax::begin(['id' => 'shop-item-bind-search']) ?>
	<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
	<div class="row">
		<div class="col-xs-12">
			Поставщик:
			<?php echo Html::dropDownList(
					'providerId',
					$provider->id,
					$providerList,
					['class' => 'form-control', 'style' => 'width: auto; display: inline-block; margin-bottom: 5px;']
			); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<?php echo Html::textInput('wordSearch', $wordSearchQuery, [
				'placeholder' => 'Введите часть названия',
				'class' => 'form-control',
				'style' => 'margin-bottom: 5px;'
			]); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<?php echo Html::textInput('search', $searchQuery, ['placeholder' => 'Введите поисковые запрос', 'class' => 'form-control']); ?>
		</div>
		<div class="col-xs-4">
			<?= Html::submitButton('Найти', ['class' => 'btn btn-success btn-search', 'style' => 'width: 100%;']) ?>
		</div>
	</div>
	<br/>
	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'tableOptions' => [
			'class' => 'table table-striped table-bordered',
			'data-provider-id' => $provider->id,
			'id' => 'shop-item-provider-item-table'
		],
		'summary' => "Найдено позиций: <b>{count}</b>",
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'vendor_code',
			'title',
			'price',
			'unit',
			'manufacturer',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{link} {unlink} {list}',
				'buttons' => [
					'link' => function ($url, $model) use ($shopItemId) {
						/* @var $model \app\modules\pms\models\ProviderItem */
						return Html::a(Html::icon('ok-sign'), $url . '&shopItemId=' . $shopItemId,
							['title' => 'Связать', 'class' => 'btn-link-item' . ($model->checkShopItemLink($shopItemId) ? ' hidden' : '')]);
					},
					'list' => function ($url) {
						return Html::a(Html::icon('list'), $url, ['title' => 'Показать в прайсе', 'class' => 'btn-show-in-list']);
					},
				],
				'options' => [
					'width' => '50px',
				],
			],
		],
	]); ?>

	<?php ActiveForm::end(); ?>
	<?php Pjax::end(); ?>
</div>
