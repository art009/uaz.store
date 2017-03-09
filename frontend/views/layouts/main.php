<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $controllerId string */
/* @var $actionId string */

use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use frontend\widgets\NavMenu;

AppAsset::register($this);

$controllerId = Yii::$app->controller->id;
$actionId = Yii::$app->controller->action->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php if ($GATrackingID = (Yii::$app->params['GATrackingID'] ?? null)): ?>
	<!-- Google Analytics -->
	<script>
		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
		//ga('create', 'UA-93217412-1', 'auto')
		//ga('send', 'pageview');
	</script>
	<script async src="https://www.google-analytics.com/analytics.js"></script>
	<!-- End Google Analytics -->
<?php endif; ?>
<div class="wrap">
    <?php NavBar::begin([
        'brandLabel' => Html::img('/img/logo.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);?>
	<?php echo NavMenu::widget(); ?>
	<?php echo Nav::widget([
		'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
			[
                'label' => Html::icon('search'),
                'url' => ['/search'],
				'active' => ($controllerId == 'catalog' && $actionId == 'search'),
            ],
			[
                'label' => Html::icon('shopping-cart'),
                'url' => ['/cart'],
                'linkOptions' => [
                    'class' => 'cart-link',
                    'data-count' => Yii::$app->cart->getQuantity() ? : null,
                ],
                'active' => ($controllerId == 'cart'),
            ],
			[
                'label' => Html::icon('user'),
                'url' => ['/user'],
				'active' => ($controllerId == 'user'),
            ],
		],
    ]);?>

    <div class="nav-contacts">
        <div class="nav-phone">
            <span>+7 (800) </span>00-00-00
        </div>
        <div class="nav-email">
            <a href="#">support@uaz.store</a>
        </div>
    </div>
    <?php NavBar::end();?>

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
                    <?php echo Html::a(Html::icon('earphone'), '#', [
						'class' => 'link-icon',
						'data-toggle' => 'tooltip',
						'data-placement' => 'top',
						'title' => 'Заказать обратный звонок',
					]); ?>
                </span>
                <span class="icon-link-outer">
                    <?php echo Html::a(Html::icon('envelope'), '#', [
                        'class' => 'link-icon',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'title' => 'Задать вопрос',
                    ]); ?>
                </span>
            </p>
            <p class="pull-right">
                <?php echo Html::icon('map-marker'); ?>г. Пенза, ул. Пугачева, 3
            </p>
        </div>
        <div class="col-xs-12 col-sm-6 footer-right">
            <p class="pull-left">
				<a href="/price-list"><?php echo Html::icon('download-alt'); ?>Прайс-лист <i>от  <?= date('d.m.Y') ?>г.</i></a>
            </p>
            <p class="pull-right">
                <span class="icon-link-outer">
				    <?php echo Html::a('В', 'https://vk.com/uaz_zapchasty', [
						'target' => '_blank',
                        'rel' => 'nofollow',
						'class' => 'link-icon social',
						'data-toggle' => 'tooltip',
						'data-placement' => 'top',
						'title' => 'Перейти в группу ВКонтакте',
					]); ?>
                </span>
            </p>
        </div>
    </div>
</footer>
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Organization",
  "name" : "UAZ.STORE",
  "url" : "https://uaz.store",
  "sameAs" : [
    "https://vk.com/uaz_zapchasty",
  ]
}
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
