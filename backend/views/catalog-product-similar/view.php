<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogProductSimilar */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Аналогичные товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="catalog-product-similar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Отредактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить аналогичный товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'productTitle:raw',
            'similarProductTitle:raw',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
