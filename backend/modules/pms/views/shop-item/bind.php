<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ShopItem */
/* @var $provider \app\modules\pms\models\Provider */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchQuery string */

$this->title = $model->title . ' [ ' . $model->vendor_code . ' ]';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Товары магазина', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Связывание';
?>
<div class="shop-item-bind">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="alert alert-info">
		...тут должен быть блок с таблицей текущих связей!
	</div>
	<?php Pjax::begin(['id' => 'shop-item-bind-search']) ?>
	<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
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
					'link' => function ($url, $model, $key) {
						return false ? Html::a(Html::icon('ok-sign'), $url, ['title' => 'Связать']) : ''; // Пока скрыта
					},
					'unlink' => function ($url, $model, $key) {
						return false ? Html::a(Html::icon('remove-sign'), $url, ['title' => 'Отвязать']) : ''; // Пока скрыта
					},
					'list' => function ($url, $model, $key) {
						return Html::a(Html::icon('list'), $url, ['title' => 'Показать в прайсе', 'class' => 'btn-show-in-list']);
					},
				],
			],
		],
	]); ?>

	<?php ActiveForm::end(); ?>
	<?php Pjax::end(); ?>
</div>
