<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ProviderItem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Provider Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'provider_id',
            'code',
            'vendor_code',
            'title',
            'price',
            'unit',
            'manufacturer',
            'rest',
            'ignored',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
