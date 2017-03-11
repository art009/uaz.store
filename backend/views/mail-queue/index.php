<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Очедедь писем';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-queue-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php echo Html::a('Отправить письма', ['send'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
	        'id',
            [
                'attribute' => 'status',
                'value' => function($data) {
                    /* @var $data \backend\models\MailQueue */
                    return $data->getStatusLabel();
                }
            ],
            'to',
            'subject',
            'text:html',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>
