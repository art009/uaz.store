<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model common\models\CatalogManualPage */
/* @var $manual backend\models\CatalogManual|null */
/* @var $category backend\models\CatalogCategory|null */


$this->title = 'Страница справочника ' . $manual->title . ': ' . $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/catalog-manual/index']];
$this->params['breadcrumbs'][] = ['label' => 'Справочник: ' . $manual->title , 'url' => ['/catalog-manual/view', 'id' => $manual->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-manual-page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'cid' => $model->category_id, 'mid' => $model->manual_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'cid' => $model->category_id, 'mid' => $model->manual_id], [
            'class' => 'btn btn-danger',
            'data' => [
				'confirm' => 'Вы уверены, что хотите удалить эту страницу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			[
				'attribute' => 'manual_id',
				'value' => $manual->title,
			],
            [
				'attribute' => 'category_id',
				'value' => $category->title,
			],
			[
				'attribute' => 'image',
				'format' => 'raw',
				'value' => $model->image ? Html::a(
					Html::img(AppHelper::uploadsPath() . '/' . $model::FOLDER_MEDIUM . '/' . $model->image),
					AppHelper::uploadsPath() . '/' . $model::FOLDER . '/' . $model->image,
					['data-fancybox' => true]
				) : null,
			],
            'meta_keywords:ntext',
            'meta_description:ntext',
            'description:html',
			[
				'attribute' => 'hide',
				'value' => AppHelper::$yesNoList[$model->hide],
			],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
