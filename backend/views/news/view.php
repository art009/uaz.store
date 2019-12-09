<?php

use common\models\News;
use yii\helpers\Html;

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
        <b>Картинка:</b>
        <br/>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <a href="<?php echo $model->getImagePath(false); ?>" target="_blank" class="thumbnail" title="Оригинал">
                    <img src="<?php echo $model->getImagePath(false); ?>" alt="image">
                </a>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="row">
                    <a href="<?php echo $model->getImagePath(true); ?>" target="_blank" class="thumbnail" title="Размер в списке новостей">
                        <img src="<?php echo $model->getImagePath(true); ?>" alt="image" width="<?php echo News::SMALL_IMAGE_WIDTH; ?>" height="<?php echo News::SMALL_IMAGE_HEIGHT; ?>">
                    </a>
                </div>
                <div class="row">
                    <a href="<?php echo $model->getImagePath(false); ?>" target="_blank" class="thumbnail" title="Размер на странице новости">
                        <img src="<?php echo $model->getImagePath(false); ?>" alt="image" width="<?php echo News::MEDIUM_IMAGE_WIDTH; ?>" height="<?php echo News::MEDIUM_IMAGE_HEIGHT; ?>">
                    </a>
                </div>
            </div>
        </div>
    </p>
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
