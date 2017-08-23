<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pms\models\ProviderItem */

$this->title = 'Create Provider Item';
$this->params['breadcrumbs'][] = ['label' => 'Provider Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
