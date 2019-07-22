<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\User;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$name = implode(' | ', [
	'ID:' . $model->id,
	$model->email,
	$model->phone ? '+7' . $model->phone : null
]);
$this->title = 'Пользователь ' . $name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
        $attributes = [
			'id',
			'email:email',
            [
				'attribute' => 'phone',
				'value' => $model->phone ? '+7' . $model->phone : null,
			],
            [
				'attribute' => 'status',
				'value' => $model->getStatusName(),
			],
			[
				'attribute' => 'role',
				'value' => $model->getRoleName(),
			],
        ];

        if (in_array($model->role, [User::ROLE_CLIENT, User::ROLE_MANAGER])) {
			$attributes[] = [
				'attribute' => 'legal',
				'value' => $model->getLegalName(),
			];
        }

        $attributes[] = 'name';
	    $attributes[] = [
		    'attribute' => 'image',
			'format' => 'raw',
			'value' => $model->photo ? Html::a(
			    Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_SMALL . '/' . $model->photo),
				AppHelper::uploadsPath() . '/' . $model::FOLDER . '/' . $model->photo,
				['data-fancybox' => true]
            ) : null,
        ];


	    if ($model->role == User::ROLE_CLIENT && $model->legal == User::LEGAL_NO) {
			$attributes[] = 'passport_series';
			$attributes[] = 'passport_number';
        }

        if ($model->role == User::ROLE_CLIENT && $model->isLegal()) {
			$attributes[] = 'inn';
			$attributes[] = 'kpp';
			$attributes[] = 'representive_name';
			$attributes[] = 'representive_position';
			$attributes[] = 'bank_name';
			$attributes[] = 'bik';
            $attributes[] = 'account_number';
            $attributes[] = 'correspondent_account';
        }

        if ($model->role == User::ROLE_CLIENT) {
			$attributes[] = 'postcode';
			$attributes[] = 'address';
			$attributes[] = 'fax';
			$attributes[] = [
				'attribute' => 'offer_accepted',
				'value' => AppHelper::$yesNoList[$model->offer_accepted],
			];
			$attributes[] = 'accepted_at';
        }

	    $attributes[] = 'created_at';
	    $attributes[] = 'updated_at';

        echo DetailView::widget([
            'model' => $model,
            'attributes' => $attributes,
        ]);
    ?>

</div>
