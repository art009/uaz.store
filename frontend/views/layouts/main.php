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
use common\widgets\Alert;

AppAsset::register($this);

$controllerId = Yii::$app->controller->id;
$actionId = Yii::$app->controller->action->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
        NavBar::begin([
            'brandLabel' => Html::img('/img/logo.png'),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
    ?>
	<?php
    echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-left'],
		'items' => [
			['label' => 'Товары', 'url' => ['/catalog']],
			['label' => 'О компании', 'url' => ['/about']],
			['label' => 'Оплата и доставка', 'url' => ['/delivery']],
			['label' => 'Отзывы', 'url' => ['/reviews']],
        ],
	]);
	?>
	<?php

    /*if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->name . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }*/
    echo Nav::widget([
		'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
			['label' => Html::icon('search'), 'url' => ['/search']],
			['label' => Html::icon('shopping-cart'), 'url' => ['/cart'], 'linkOptions' => [
                'class' => 'cart-link',
			    'data-count' => Yii::$app->cart->getQuantity() ? : null,
            ], 'active' => ($controllerId == 'cart')],
			['label' => Html::icon('user'), 'url' => ['/user']],
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
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
