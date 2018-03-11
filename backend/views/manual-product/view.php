<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ManualProduct */
/* @var $category common\models\ManualCategory */

$this->title = $model->title;
$this->params['breadcrumbs'] = $category->createBackendBreadcrumbs(false);
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['/manual-category/view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить позицию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'manual_category_id',
            'product_id',
            'number',
            'code',
            'title',
            'left',
            'top',
            'width',
            'height',
            'positions:ntext',
            'hide',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
