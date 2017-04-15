<?php

/* @var $this yii\web\View */
/* @var $model \common\models\CatalogManual */

use yii\helpers\Html;

$this->title = 'Справочник';
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/manual']];
$this->params['breadcrumbs'][] = $model->title;

?>
<div class="manual-view">
    <h1><?= Html::encode($model->title) ?></h1>
	<p>
		Пустой справочник
	</p>
</div>
