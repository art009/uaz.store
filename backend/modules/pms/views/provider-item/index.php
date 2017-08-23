<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pms\models\ProviderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Provider Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('Импорт из Excel', ['import'], ['class' => 'btn btn-success']) ?>
	</p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'provider_id',
            'code',
            'vendor_code',
            'title',
            // 'price',
            // 'unit',
            // 'manufacturer',
            // 'rest',
            // 'ignored',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
