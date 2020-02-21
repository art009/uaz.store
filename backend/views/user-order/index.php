<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'email:email',
            'phone',
            'legal',
            'name',
            //'passport_series',
            //'passport_number',
            //'inn',
            //'kpp',
            //'postcode',
            //'address',
            //'fax',
            //'representive_name',
            //'representive_position',
            //'bank_name',
            //'bik',
            //'account_number',
            //'correspondent_account',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
