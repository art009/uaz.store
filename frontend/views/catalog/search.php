<?php

use frontend\widgets\ProductSearch;

/* @var $this \yii\web\View */

$this->title = 'Поиск товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?php echo $this->title ?></h1>
<?php echo ProductSearch::widget(); ?>
