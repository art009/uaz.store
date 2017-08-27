<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\pms\models\ShopItem;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pms\models\ShopItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары магазина';
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Импорт из Excel', ['import'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Пересчитать', ['calculate'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Пересчитать и выгрузить', ['export'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'vendor_code',
            'title',
            'price',
            'site_price',
            'unit',
            [
	            'attribute' => 'status',
	            'value' => 'statusLabel',
	            'filter' => ShopItem::$statusList,
            ],
            [
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{view}{update}{bind}',
	            'buttons' => [
		            'bind' => function ($url) {
			            return Html::a(Html::icon('transfer'), $url, ['title' => 'Связать']);
		            },
	            ],
            ],
        ],
    ]); ?>
</div>
