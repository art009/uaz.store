<?php

/* @var $this \frontend\components\SeoView */
/* @var $content string */
/* @var $controllerId string */

/* @var $actionId string */

use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use frontend\widgets\CallbackWidget;
use frontend\widgets\FastOrderWidget;
use frontend\widgets\NavMenu;
use frontend\widgets\QuestionWidget;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

$controllerId = Yii::$app->controller->id;
$actionId = Yii::$app->controller->action->id;
$this->addSeoMetatags();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <meta name="yandex-verification" content="57bf07e53461193f" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - UAZ.STORE</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php if ($GATrackingID = (Yii::$app->params['GATrackingID'] ?? null)): ?>
    <!-- Google analytics -->
    <script>
      (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r
        i[r] = i[r] || function () {
          (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date()
        a = s.createElement(o),
          m = s.getElementsByTagName(o)[0]
        a.async = 1
        a.src = g
        m.parentNode.insertBefore(a, m)
      })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga')

      ga('create', 'UA-149747736-1', 'auto')
      ga('require', 'displayfeatures')
      ga('send', 'pageview')

      /* Accurate bounce rate by time */
      if (!document.referrer ||
        document.referrer.split('/')[2].indexOf(location.hostname) != 0)
        setTimeout(function () {
          ga('send', 'event', 'Новый посетитель', location.pathname)
        }, 15000)</script>
    <!-- /Google analytics -->

    <!-- Yandex.Metrika counter -->
        <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(56268205, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/56268205" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
<?php endif; ?>
<div class="wrap">
    <?php NavBar::begin([
        'brandLabel' => Html::img('/img/logo.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]); ?>
    <?php echo NavMenu::widget(); ?>
    <?php echo Nav::widget([
        'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            [
                'label' => Html::icon('search'),
                'url' => ['/search'],
                'active' => ($controllerId == 'catalog' && $actionId == 'search'),
                'linkOptions' => [
                    'data-tooltip' => 'tooltip',
                    'data-trigger' => 'hover',
                    'data-placement' => 'bottom',
                    'title' => 'Поиск товара',
                ],
            ],
            [
                'label' => Html::icon('shopping-cart'),
                'url' => ['/cart'],
                'linkOptions' => [
                    'class' => 'cart-link',
                    'data-count' => Yii::$app->cart->getQuantity() ?: null,
                    'data-tooltip' => 'tooltip',
                    'data-trigger' => 'hover',
                    'data-placement' => 'bottom',
                    'title' => 'Корзина',
                ],
                'active' => ($controllerId == 'cart'),
            ],
            [
                'label' => Html::icon('user'),
                'url' => ['/user'],
                'linkOptions' => [
                    'data-tooltip' => 'tooltip',
                    'data-trigger' => 'hover',
                    'data-placement' => 'bottom',
                    'title' => 'Личный кабинет',
                ],
                'active' => ($controllerId == 'user'),
            ],
        ],
    ]); ?>

    <div class="nav-contacts">
        <div class="nav-phone">
            <a href="tel:88002016095">8 800 201-60-95</a>
        </div>
        <small>Звонок по России бесплатный</small>
    </div>
    <?php NavBar::end(); ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
    <?php echo Alert::widget(); ?>
</div>

<footer class="footer">
    <div class="container">
        <div class="col-xs-12 col-sm-6 footer-left">
            <p class="pull-left">
                <span class="icon-link-outer">
                    <?php echo Html::a(Html::icon('earphone'), '#callback-form-modal', [
                        'class' => 'link-icon',
                        'data-toggle' => 'modal',
                        'data-tooltip' => 'tooltip',
                        'data-trigger' => 'hover',
                        'data-placement' => 'top',
                        'title' => 'Заказать обратный звонок',
                        'data-target' => '#callback-form-modal',
                    ]); ?>
                </span>
                <span class="icon-link-outer">
                    <?php echo Html::a(Html::icon('envelope'), '#', [
                        'class' => 'link-icon',
                        'data-toggle' => 'modal',
                        'data-tooltip' => 'tooltip',
                        'data-trigger' => 'hover',
                        'data-placement' => 'top',
                        'title' => 'Задать вопрос',
                        'data-target' => '#question-form-modal',
                    ]); ?>
                </span>
            </p>
            <p class="pull-right">
                <?php echo Html::icon('map-marker'); ?>г. Пенза, ул. Пугачева, 3
            </p>
        </div>
        <div class="col-xs-12 col-sm-6 footer-right">
            <p class="pull-left">
                <a href="/price-list"><?php echo Html::icon('download-alt'); ?>Прайс-лист <i>от <?= date('d.m.Y') ?>
                        г.</i></a>
            </p>
            <p class="nav-email">
                <a href="mailto:support@uaz.store">support@uaz.store</a>
            </p>
            <p class="pull-right">
                <span class="icon-link-outer">
				    <?php echo Html::a('В', 'https://vk.com/uaz.store', [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                        'class' => 'link-icon social',
                        'data-tooltip' => 'tooltip',
                        'data-trigger' => 'hover',
                        'data-placement' => 'top',
                        'title' => 'Перейти в группу ВКонтакте',
                    ]); ?>
                </span>
            </p>
        </div>
    </div>
</footer>
<?php echo CallbackWidget::widget(); ?>
<?php echo QuestionWidget::widget(); ?>
<?php echo FastOrderWidget::widget(); ?>
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Organization",
        "name": "UAZ.STORE",
        "url": "https://uaz.store",
        "sameAs": [
            "https://vk.com/uaz.store"
        ]
    }
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
