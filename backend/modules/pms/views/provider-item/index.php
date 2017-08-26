<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pms\models\ProviderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $provider \app\modules\pms\models\Provider */

$this->title = 'Товары поставщика ' . $provider->name;
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Поставщики', 'url' => ['/pms/provider']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="provider-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('Импорт из Excel', ['import', 'providerId' => $provider->id], ['class' => 'btn btn-success']) ?>
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
            'unit',
            'manufacturer',
            'rest',
            // 'ignored',
            // 'created_at',
            // 'updated_at',

            [
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>
