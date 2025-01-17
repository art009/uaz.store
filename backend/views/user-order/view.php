<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UserOrder */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Покупатели', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'phone',
            'legal',
            'name',
            'passport_series',
            'passport_number',
            'inn',
            'kpp',
            'postcode',
            'address',
            'fax',
            'representive_name',
            'representive_position',
            'bank_name',
            'bik',
            'account_number',
            'correspondent_account',
        ],
    ]) ?>

</div>
