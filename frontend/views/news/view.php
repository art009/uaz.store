<?php

/* @var $this yii\web\View */
/* @var $news \common\models\News */

use common\models\News;
use yii\helpers\Html;

$this->title = $news->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['/news']];
//$this->params['breadcrumbs'][] = $this->title;

if (!empty($news->meta_keywords)) {
    $this->params['keywords'] = $news->meta_keywords;
}
if (!empty($news->meta_description)) {
    $this->params['description'] = $news->meta_description;
}

?>
<div class="news">
    <h1>&nbsp;</h1>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <a href="" class="thumbnail">
                <img src="<?php echo $news->getImagePath(false); ?>" alt="image" width="<?php echo News::MEDIUM_IMAGE_WIDTH; ?>" height="<?php echo News::MEDIUM_IMAGE_HEIGHT; ?>">
            </a>
        </div>
        <div class="col-xs-12 col-md-8">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <small><?php echo (new \DateTime($news->created_at))->format('Y-m-d H:i'); ?></small>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <?php echo $news->description; ?>
                </div>
            </div>
        </div>
    </div>
</div>
