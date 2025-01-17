<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Manual */
/* @var $category \common\models\ManualCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = $category->title . ' на ' . $model->title;
$this->params['breadcrumbs'] = $category->createBreadcrumbs();

?>
<div class="manual-view">
    <h1><?= Html::encode($category ? $category->title : $model->title) ?></h1>
		<?php $image = $category ? $category->getImagePath() : null; ?>
		<?php if ($image): ?>
		<div class="manual-page-image">
			<div class="manual-page-container">
				<?php echo Html::img($image); ?>
				<?php if ($category->manualProducts): ?>
					<?php foreach ($category->manualProducts as $manualProduct): ?>
						<?php
						$styles = Html::cssStyleFromArray([
							'left' => $manualProduct->left . 'px',
							'top' => $manualProduct->top . 'px',
							'width' => $manualProduct->width . 'px',
							'height' => $manualProduct->height . 'px',
						]);
						?>
						<div class="image-product" id="<?= $manualProduct->id ?>" style="<?php echo $styles; ?>"><?php echo $manualProduct->number; ?></div>
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

	    <?= GridView::widget([
			'dataProvider' => $dataProvider,
			'rowOptions' => function ($model, $key, $index, $grid) {

				return ['class' => 'manual-product-row','id' => 'row' . $model['id']];
			},
				'summary'=>'',
				'columns' => [
                'number',
                'code',
				'title',
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '{buy}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action == 'buy') {
                            if (sizeof($model->catalogProducts)>0) {
                                if (sizeof($model->catalogProducts)>1) {
                                    return \yii\helpers\Url::to(['catalog/product-list', 'manual_product_id' => $model->id]);
                                } else {
                                    $result = false;
                                    $resultCatalogProduct = false;
                                    foreach ($model->catalogProducts as $i => $catalogProduct) {
                                        if (sizeof($catalogProduct->categories)>0) {
                                            $result = $i;
                                        }
                                    }
                                    if ($result === false) {
                                        return false;
                                    }
                                    $catalogProduct = $model->catalogProducts[$result];
                                    $category = $catalogProduct->categories[0];
                                    if (!$category) {
                                        return false;
                                    }
                                    return \yii\helpers\Url::to(['catalog/product', 'id' => $catalogProduct->id, 'categoryId' => $category->id]);
                                }
                            }

                            return false;
                        }
                    },
					'buttons' => [
						'buy' => function ($url, $model) {
	                        if (sizeof($model->catalogProducts)>1) {
                                return Html::a(
                                    '<div class="site-btn open-catalog" data-id="' . $model->id . '">Узнать цену</div>',
                                    $url, ['class' => 'open-catalog', 'target' => '_blank']);
                            }
	                        if (sizeof($model->catalogProducts)>0) {
	                            $result = false;
	                            foreach ($model->catalogProducts as $i => $catalogProduct) {
	                                if (sizeof($catalogProduct->categories)>0) {
	                                    $result = $i;
                                    }
                                }
	                            if ($result === false) {
	                                return '';
                                }
	                            $catalogProduct = $model->catalogProducts[$result];
                                $category = $catalogProduct->categories[0];
                                if (!$category) {
                                    return '';
                                }
								return Html::a(
									'<div class="site-btn open-catalog" data-id="' . $model->id . '">Узнать цену</div>',
									$url, ['class' => 'open-catalog', 'target' => '_blank']);
							}
							return null;
						},
                    ]
				],
			],
		]); ?>
		<?php endif; ?>
</div>
