<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
            [
				'attribute' => 'phone',
				'value' => function ($model) {
					/* @var $model \backend\models\User */
					return  $model->phone ? '+7' . $model->phone : null;
				},
			],
			[
				'attribute' => 'status',
				'value' => function ($model) {
					/* @var $model \backend\models\User */
					return \backend\models\User::$statusList[$model->status];
				},
				'filter' => $searchModel::$statusList,
			],
			[
				'attribute' => 'role',
				'value' => function ($model) {
					/* @var $model \backend\models\User */
					return \backend\models\User::$roleList[$model->role];
				},
				'filter' => $searchModel::$roleList,
			],
			[
				'attribute' => 'legal',
				'value' => function ($model) {
                    /* @var $model \backend\models\User */
					return \backend\models\User::$legalList[$model->legal];
				},
				'filter' => $searchModel::$legalList,
			],
			[
				'attribute' => 'name',
                'format' => 'html',
				'value' => function ($model) {
					/* @var $model \backend\models\User */
					$html = $model->name;
					if ($model->role == \common\models\User::ROLE_CLIENT) {
					    if ($model->orders) {
						    $html .= '<br/>' . Html::a('заказы (' . count($model->orders) . ')', ['/order', 'userId' => $model->id], ['class' => 'btn btn-primary btn-xs']);
					    }
					    $html .= '<br/>' . Html::a('добавить заказ', ['/order/create', 'userId' => $model->id], ['class' => 'btn btn-success btn-xs']);
				    }

					return $html;
				},
			],
			[
				'attribute' => 'offer_accepted',
				'value' => function ($model) {
					return AppHelper::$yesNoList[$model->offer_accepted];
				},
				'filter' => AppHelper::$yesNoList,
			],
			[
				'attribute' => 'created_at',
				'filter' => false,
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
