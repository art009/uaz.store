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
            'name',
			[
				'attribute' => 'deleted',
				'value' => function($model) {
    	            return AppHelper::$yesNoList[$model->deleted];
				},
				'filter' => AppHelper::$yesNoList,
			],
	        [
		        'label' => 'Товары',
		        'format' => 'raw',
		        'value' => function($model) {
    	            /* @var $model \app\modules\pms\models\Provider */
    	            $count = $model->getItems()->count();
			        return $count . ' ' . Html::a(
			        		($count > 0 ? 'Список' : 'Импорт') . ' товаров',
					        ['provider-item/' . ($count > 0 ? 'index' : 'import') , 'providerId' => $model->id]
				        );
		        }
	        ],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {update}',
			],
		],
    ]); ?>
</div>
