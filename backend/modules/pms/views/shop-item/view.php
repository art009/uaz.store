<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ShopItem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Товары магазина', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Связать', ['bind', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'vendor_code',
            'title',
            'price',
            'site_price',
	        'statusLabel',
            'percent',
            'unit',
            [
	            'attribute' => 'ignored',
	            'value' => AppHelper::$yesNoList[$model->ignored],
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
