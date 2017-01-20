<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CatalogProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Catalog Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'category_id',
            'title',
            'link',
            'image',
            // 'meta_keywords:ntext',
            // 'meta_description:ntext',
            // 'price',
            // 'price_to',
            // 'price_old',
            // 'shop_title',
            // 'provider_title',
            // 'shop_code',
            // 'provider_code',
            // 'description:ntext',
            // 'hide',
            // 'on_main',
            // 'provider',
            // 'manufacturer',
            // 'cart_counter',
            // 'length',
            // 'width',
            // 'height',
            // 'weight',
            // 'unit',
            // 'rest',
            // 'external_id',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
