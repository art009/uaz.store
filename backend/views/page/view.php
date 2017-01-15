<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Текстовые страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">
    <p>
        <?= Html::a('Посмотреть на сайте', Yii::$app->params['frontendUrl'] . $model->link, [
            'class' => 'btn btn-info',
            'target' => '_blank'
        ]) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить страницу?',
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
    <p>
        <b>Текст метатега description:</b>
        <br/>
        <code>
            <?php echo $model->meta_description; ?>
        </code>
    </p>
    <pre>
        <?php echo $model->description; ?>
    </pre>
</div>
