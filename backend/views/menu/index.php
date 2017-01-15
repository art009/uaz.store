<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\AppHelper;
use common\components\SortableActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пункт меню', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'link',
            [
                'attribute' => 'hide',
                'value' => function ($model) {
                    return AppHelper::$hiddenList[$model->hide];
                },
                'filter' => AppHelper::$hiddenList,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}'
            ],
            [
                'class' => SortableActionColumn::className(),
            ],
        ],
    ]); ?>
</div>
