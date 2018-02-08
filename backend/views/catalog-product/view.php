<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\CatalogProduct */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории товаров', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
$link = $model->getFullLink();
if ($link) {
	$link = Yii::$app->params['frontendUrl'] . trim($link, '/');
}
?>
<div class="catalog-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
	    <?php if ($link): ?>
        <?= Html::a('Просмотр на сайте', $link, [
            'class' => 'btn btn-info',
            'target' => '_blank',
        ]) ?>
	    <?php endif; ?>
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
                'attribute' => 'category_ids',
				'format' => 'html',
                'value' => function($model) {
                    $result = null;
                    if ($model->categories) {
                        $categories = [];
                        foreach ($model->categories as $category) {
                            $categories[] = $category->title;
                        }
                        $result = implode('<br/>', $categories);
                    }
                    return $result;
                },
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
            'oversize:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
