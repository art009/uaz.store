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

$this->title = $model->title . ' [ ' . $model->vendor_code . ' ]';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Товары магазина', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Связывание';

$shopItemId = $model->id;
?>
<div class="shop-item-bind">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		Установленные связи:
	</p>
    <?php echo GridView::widget([
        'dataProvider' => $linkDataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'shop-item-link-table'
        ],
		'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'vendor_code',
			'title',
			'price',
			'unit',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{unlink}',
                'buttons' => [
					'unlink' => function ($url) use ($shopItemId) {
						return Html::a(Html::icon('remove-sign'), $url . '&shopItemId=' . $shopItemId, ['title' => 'Отвязать', 'class' => 'btn-unlink-item']);
					},
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
