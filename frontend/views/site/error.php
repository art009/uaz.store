<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception \yii\web\HttpException */

use yii\helpers\Html;

$this->title = 'Ошибка ' . $exception->statusCode;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
	    Вернуться <a href="/">на главную</a> страницу.
    </p>

</div>
