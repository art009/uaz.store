<?php

/* @var $this yii\web\View */
/* @var $models \common\models\News[] */
/* @var $pages \yii\data\Pagination */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\models\News;

$this->title = 'Новости';
$this->params['breadcrumbs'][] = 'Новости';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($models): ?>
        <div class="news">
            <?php foreach ($models as $model): ?>
                <div class="row">
                    <div class="col-xs-4 col-md-2">
                        <a href="/news/<?php echo $model->id; ?>" title="<?php echo $model->title; ?>" class="thumbnail">
                            <img src="<?php echo $model->getImagePath(); ?>" alt="image" width="<?php echo News::SMALL_IMAGE_WIDTH; ?>" height="<?php echo News::SMALL_IMAGE_HEIGHT; ?>">
                        </a>
                    </div>
                    <div class="col-xs-8 col-md-10">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <small><?php echo (new \DateTime($model->created_at))->format('Y-m-d H:i'); ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <a href="/news/<?php echo $model->id; ?>" title="<?php echo $model->title; ?>">
                                    <strong><?php echo $model->title; ?></strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Не найдено.</p>
    <?php endif; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>
