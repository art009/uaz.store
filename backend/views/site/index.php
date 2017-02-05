<?php

/* @var $this yii\web\View */

$this->title = 'Административная панель';
?>
<div class="site-index text-center">
    <h1>Система управления сайтом</h1>
    <p class="lead">Выберите необходимый раздел</p>
    <p>
        <a class="btn btn-md btn-info btn-block" href="/menu">Меню</a>
    </p>
    <p>
        <a class="btn btn-md btn-primary btn-block" href="/page">Текстовые страницы</a>
    </p>
    <p>
        <a class="btn btn-md btn-danger btn-block" href="/catalog">Каталог</a>
    </p>
    <p>
        <a class="btn btn-md btn-warning btn-block" href="/catalog-manual">Справочники</a>
    </p>
    <p>
        <a class="btn btn-md btn-success btn-block" href="/user">Пользователи</a>
    </p>
</div>
