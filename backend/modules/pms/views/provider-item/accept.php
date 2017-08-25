<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \backend\modules\pms\models\ProviderItemAcceptForm */
/* @var $poviderId \app\modules\pms\models\ProviderItem*/


$this->title = 'Товары, требующие подтверждения';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Поставщики', 'url' => ['/pms/provider']];
$this->params['breadcrumbs'][] = ['label' => 'Товары поставщика', 'url' => ["index?providerId=$providerId"]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="provider-item-accept">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php $form = ActiveForm::begin([
		'method' => 'post',
		'action' => ["provider-item/accept?providerId=$providerId"],
    ]); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'class' => 'yii\grid\CheckboxColumn',
				'checkboxOptions' => function ($item) {
					return ['value' => $item['code'],'label' => "Обновить"];
				},
				'name' => $model->formName() . '[accept][]',
			],
			[
				'class' => 'yii\grid\CheckboxColumn',
				'checkboxOptions' => function ($item) {
					return ['value' => $item['code'],'label' => "Игнорировать"];
				},
				'name' => $model->formName() . '[ignored][]',
			],
            [
                'label'=>'Код',
                'value' => 'code'
            ],
			[
				'label'=>'Код у поставщика',
				'value' => 'vendor_code'
			],
			[
				'label'=>'Наименование',
				'value' => 'title'
			],
			[
				'label'=>'Ед. измерения',
				'value' => 'unit'
			],
			[
				'label'=>'Остаток',
				'value' => 'rest'
			],
			[
				'label'=>'Производитель',
				'value' => 'manufacturer'
			],
			[
				'label'=>'Старая цена',
				'value' => 'old_price'
			],
			[
				'label'=>'Новая цена',
				'value' => 'price'
			],
        ],
	]); ?>
    <div class="form-group">
		<?= Html::submitButton('Принять', ['class' => 'btn btn-success']) ?>
		<?= Html::a('Отмена', ['cancel', 'providerId' => $providerId], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'Вы уверены?',
				'method' => 'post',
			],
		]) ?>
    </div>
	<?php ActiveForm::end(); ?>
</div>