<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CatalogManualPage */
/* @var $manual backend\models\Manual|null */
/* @var $category backend\models\CatalogCategory|null */

$this->title = 'Добавление страницы справочника ' . $manual->title . ': ' . $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/catalog-manual/index']];
$this->params['breadcrumbs'][] = ['label' => 'Справочник: ' . $manual->title , 'url' => ['/catalog-manual/view', 'id' => $manual->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-manual-page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
