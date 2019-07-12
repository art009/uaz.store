<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Административная панель UAZ.STORE</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Административная панель',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'PMS', 'url' => ['/pms'],  'linkOptions' => ['class' => 'text-success']],
        ['label' => 'Главная страница', 'url' => Yii::$app->params['frontendUrl'], 'linkOptions' => ['target' => '_blank']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->email . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => [
                'label' => 'Административная панель',
                'url' => Yii::$app->homeUrl,
            ],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; uaz.store <?= date('Y') ?></p>

        <p class="pull-right">Разработано <a href="http://dvmpnz.ru/" target="_blank">dvmpnz</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
} ?>
<?php $this->endPage() ?>
