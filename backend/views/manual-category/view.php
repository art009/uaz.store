<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ManualCategory */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'] = $model->createBackendBreadcrumbs();
?>
<div class="manual-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить категорию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'manual_id',
            'parent_id',
            'catalog_category_id',
            'title',
            'link',
            'hide',
            'meta_keywords:ntext',
            'meta_description:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

	<?php if($model->isImageLevel()): ?>
		<?php $image = $model ? $model->getImagePath() : null; ?>
		<?php if ($image): ?>
			<h2>Чертеж</h2>
			<div class="manual-page-image">
				<div class="manual-page-container">
					<?php echo Html::img($image); ?>
					<?php if ($model->manualProducts): ?>
						<?php foreach ($model->manualProducts as $manualProduct): ?>
							<?php
							$styles = Html::cssStyleFromArray([
								'left' => $manualProduct->left . 'px',
								'top' => $manualProduct->top . 'px',
								'width' => $manualProduct->width . 'px',
								'height' => $manualProduct->height . 'px',
							]);
							?>
							<div class="image-product" id="<?= $manualProduct->number ?>" style="<?php echo $styles; ?>"><?php echo $manualProduct->number; ?></div>
							<?php $positions = $manualProduct->getPositionsArray(); ?>
							<?php if ($positions): ?>
								<?php foreach ($positions as $position): ?>
									<?php
									$styles = Html::cssStyleFromArray([
										'left' => $position['left'] . 'px',
										'top' => $position['top'] . 'px',
										'width' => $position['width'] . 'px',
										'height' => $position['height'] . 'px',
									]);
									?>
									<div class="image-product" id="<?php echo $manualProduct->id; ?>" style="<?php echo $styles; ?>"><?php echo $manualProduct->number; ?></div>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="manual-page-tools">
					<span class="tool-zoom-label">Масштаб <b>100</b>%</span>
					<span class="tool-zoom-minus">-</span>
					<span class="tool-zoom-original">100%</span>
					<span class="tool-zoom-plus">+</span>
				</div>
			</div>

			<h3>Позиции на чертеже</h3>
			<p>
				<?= Html::a('Добавить позицию', ['/manual-product/create', 'categoryId' => $model->id], ['class' => 'btn btn-success']) ?>
			</p>
			<?php echo GridView::widget([
				'dataProvider' => $dataProvider,
				'rowOptions' => function ($model, $key, $index, $grid) {
					return ['class' => 'manual-product-row', 'id' => 'row' . $model['number']];
				},
				'summary'=>'',
				'columns' => [
					'number',
					'code',
					'title',
					[
						'class' => 'yii\grid\ActionColumn',
						'buttons' => [
							'view' => function ($url, $model, $key) {
								return Html::a(Html::icon('eye-open'), ['/manual-product/view', 'id' => $model->id], [
									'title' => 'Просмотр',
									'aria-label' => 'Просмотр',
									'data-pjax' => 0,
								]);
							},
							'update' => function ($url, $model, $key) {
								return Html::a(Html::icon('pencil'), ['/manual-product/update', 'id' => $model->id], [
									'title' => 'Редактировать',
									'aria-label' => 'Редактировать',
									'data-pjax' => 0,
								]);
							},
							'delete' => function ($url, $model, $key) {
								return Html::a(Html::icon('trash'), ['/manual-product/delete', 'id' => $model->id], [
									'title' => 'Удалить',
									'aria-label' => 'Удалить',
									'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
									'data-method' => 'post',
									'data-pjax' => 0,
								]);
							},
						]
					],
				],
			]); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
