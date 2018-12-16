<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this \yii\web\View */
/* @var $searchModel \frontend\models\search\OrderSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-profile">

    <h1><?php echo Html::encode($this->title) ?></h1>
	<a href="/logout" data-method="post" class="site-btn logout-btn">Выход</a>
    <div class="order-list">

        <div class="order-header">
            <div class="col-xs-6 text-center">Заказы</div>
            <div class="col-xs-6 text-center"><a href="/user">Профиль</a></div>
        </div>
        <div class="clearfix"></div>

        <?php echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_order-item',
            'summary' => false,
            'itemOptions' => [
                'tag' => 'div',
                'class' => 'order-list__item',
            ],
            'pager' => [
                'options' => ['class' => 'pagination'],
                'prevPageLabel' => '<',
                'prevPageCssClass' => 'prev pull-left',
                'nextPageLabel' => '>',
                'nextPageCssClass' => 'next pull-right',
                'linkContainerOptions' => ['class' => 'text-center'],
                'class' => '\yii\widgets\LinkPager',
		        'maxButtonCount' => 5,
            ],
        ]); ?>

    </div>

</div>
