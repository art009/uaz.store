<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Покупатели';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
                    'width' => '40px;'
                ]
            ],
            'email:email',
            'phone',
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'options' => [
                    'width' => '50px;'
                ],
            ],
        ],
    ]); ?>
</div>
