<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">
    <p>
        <?= Html::a('Посмотреть на сайте', Yii::$app->params['frontendUrl'] .'news/'. $model->id, [
            'class' => 'btn btn-info',
            'target' => '_blank'
        ]) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить новость?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <h1><?php echo Html::encode($this->title) ?> (ID: <?php echo $model->id; ?>)</h1>
    <p>
        <b>Текст метатега keywords:</b>
        <br/>
        <code>
            <?php echo $model->meta_keywords; ?>
        </code>
    </p>
	<hr/>
    <p>
        <b>Текст метатега description:</b>
        <br/>
        <code>
            <?php echo $model->meta_description; ?>
        </code>
    </p>
	<hr/>
	<b>Содержимое новости:</b>
	<br/>
    <div>
        <?php echo $model->description; ?>
    </div>
</div>
