<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\AppHelper;
use common\widgets\TreeView;

/* @var $this yii\web\View */
/* @var $model backend\models\Manual */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-manual-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот справочник?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'link',
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
            'year',
			[
				'attribute' => 'hide',
				'value' => AppHelper::$yesNoList[$model->hide],
			],
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h3>Страницы справочника</h3>
    <?php echo TreeView::widget(['data' => $model->getCategoryTree()]); ?>
</div>
