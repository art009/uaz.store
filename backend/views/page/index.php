<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Текстовые страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
