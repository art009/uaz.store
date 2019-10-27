<?php

/* @var $this yii\web\View */

use frontend\widgets\ProductOnMain;
use frontend\widgets\SeoTextWidget;

$this->title = 'Интернет магазин автозапчастей УАЗ';
?>
<main class="site-index">
    <section>
        <h1>Найдется всё!</h1>
        <div class="separator"></div>
        <p>Подберите по справочнику необходимую деталь<br/>или<br/> просто позвоните менеджеру</p>
        <div class="blocks-list">
            <div class="col-md-4">
                <a href="/manual">
                    <div class="icon"><div class="image"></div></div>
                    Поиск<br/>по справочнику
                </a>
            </div>
            <div class="col-md-4">
                <a href="/catalog">
                    <div class="icon"><div class="image"></div></div>
                    Поиск<br/>по группам
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" data-toggle="modal" data-target="#order-form-modal">
                    <div class="icon"><div class="image"></div></div>
                    Быстрый<br/>заказ
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>
    <section>
        <h2>Популярные товары</h2>
        <div class="separator"></div>
        <p>Качественные запчасти УАЗ по лучшим ценам</p>
        <?php echo ProductOnMain::widget(); ?>
    </section>
    <section>
        <h3>О магазине UAZ.STORE</h3>
        <div class="separator"></div>
        <p>Мы делаем покупку запчастей УАЗ комфортной</p>
        <div class="main-about">
	        <?php echo SeoTextWidget::widget(); ?>
        </div>
        <div class="clearfix"></div>
    </section>
    <nav>
        <ul class="cd-vertical-nav">
            <li><a href="#1" class="nav-bullet active">1</a></li>
            <li><a href="#2" class="nav-bullet">2</a></li>
            <li><a href="#3" class="nav-bullet">3</a></li>
        </ul>
    </nav>
</main>