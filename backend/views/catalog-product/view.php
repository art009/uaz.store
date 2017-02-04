<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\CatalogProduct */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории товаров', 'url' => ['/catalog']];
if ($model->category) {
    if ($parentsList = $model->category->getParentsList(true)) {
        foreach ($parentsList as $id => $title) {
            $this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['catalog-category/index', 'id' => $id]];
        }
    }
    $this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['catalog-category/index', 'id' => $model->category->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Просмотр на сайте', Yii::$app->params['frontendUrl'] . 'catalog/' . $model->link, [
            'class' => 'btn btn-info',
            'target' => '_blank',
        ]) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'category_id',
                'value' => $model->category ? $model->category->title : null,
            ],
            'title',
            'link',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => $model->image ? Html::a(
                        Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_MEDIUM . '/' . $model->image, ['id' => 'product-main-image']),
                        AppHelper::uploadsPath() . '/' . $model::FOLDER . '/' . $model->image,
                        ['data-fancybox' => true]
                ) : null,
            ],
            [
                'attribute' => 'image',
                'label' => 'Все картинки',
                'format' => 'raw',
                'value' => $model->getImagesHtml(' ')
            ],
            'meta_keywords:ntext',
            'meta_description:ntext',
            'price',
            'shop_title',
            'provider_title',
            'shop_code',
            'provider_code',
            'description:ntext',
            [
                'attribute' => 'hide',
                'value' => AppHelper::$yesNoList[$model->hide],
            ],
            [
                'attribute' => 'on_main',
                'value' => AppHelper::$yesNoList[$model->on_main],
            ],
            'provider',
            'manufacturer',
            'cart_counter',
            'length',
            'width',
            'height',
            'weight',
            'unit',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
