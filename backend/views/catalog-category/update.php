<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogCategory */
/* @var $parentModel \common\models\CatalogCategory */

$this->title = 'Редактирование категории: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории товаров', 'url' => ['index']];
if ($parentModel) {
    if ($parentsList = $parentModel->getParentsList(true)) {
        foreach ($parentsList as $id => $title) {
            $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index', 'id' => $id]];
        }
    }
    $this->params['breadcrumbs'][] = ['label' => $parentModel->title, 'url' => ['index', 'id' => $parentModel->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
