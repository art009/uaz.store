<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>В разработке.</p>
	<a href="/logout" data-method="post" class="site-btn">Выход</a>
</div>
