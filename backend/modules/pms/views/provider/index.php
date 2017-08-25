<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pms\models\ProviderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поставщики';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить Поставщика', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
			[
				'attribute' => 'deleted',
				'value' => function($model){ return AppHelper::$yesNoList[$model->deleted]; } ,
			],

			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}  {update}  {link}',
			],
			[
				'label' => 'Ссылки к товарам поставщика',
				'format' => 'raw',
				'value' => function($model){
					return Html::a(
						'Перейти',
						"/pms/provider-item?providerId=$model->id"
					);
				}
			],
		],
    ]); ?>
</div>
