<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ProviderItem */

$this->title = 'Редактирование товара поставщика: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Система управления товарами', 'url' => ['/pms']];
$this->params['breadcrumbs'][] = ['label' => 'Товары поставщика', 'url' => ["index?providerId=$model->provider_id"]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

?>
<div class="provider-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
