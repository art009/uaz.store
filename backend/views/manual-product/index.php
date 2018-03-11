<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ManualProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manual Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Manual Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'manual_category_id',
            'product_id',
            'number',
            'code',
            //'title',
            //'left',
            //'top',
            //'width',
            //'height',
            //'positions:ntext',
            //'hide',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
