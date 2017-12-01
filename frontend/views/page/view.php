<?php

/* @var $this yii\web\View */
/* @var $page \common\models\Page */

use yii\helpers\Html;

$this->title = $page->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div>
	    <?php echo $page->description; ?>
    </div>
</div>
