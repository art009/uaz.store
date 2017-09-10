<?php

use yii\helpers\Html;
use common\components\AppHelper;
use backend\models\CatalogProduct;
use common\models\ManualProduct;

/* @var $this yii\web\View */
/* @var $model \app\modules\pms\models\ShopItem */
/* @var $providerItem \app\modules\pms\models\ProviderItem */

$product = $model->product;
$providerItem = $model->getProviderItems()->one();
?>
<style>
	img { max-width: 50%; max-height: 200px;}
	h1 { padding-right: 220px; margin-bottom: 0; }
	h2 { padding-right: 220px; margin-top: 0; }
	.shop-item-compare { position: relative; }
	.btn {
		display: inline-block;
		padding: 6px 12px;
		margin-bottom: 0;
		font-size: 14px;
		font-weight: normal;
		line-height: 1.42857143;
		text-align: center;
		white-space: nowrap;
		vertical-align: middle;
		-ms-touch-action: manipulation;
		touch-action: manipulation;
		cursor: pointer;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		background-image: none;
		border: 1px solid transparent;
		border-radius: 4px;
		position: fixed;
		top: 10px;
		color: #fff;
	}
	.btn-next {
		background-color: #337ab7;
		border-color: #2e6da4;
		right: 10px;
	}
	.btn-next:hover {
		text-decoration: none;
		background-color: #286090;
		border-color: #204d74;
	}
	.btn-assign {
		background-color: #5cb85c;
		border-color: #4cae4c;
		right: 130px;
	}
	.btn-assign:hover {
		text-decoration: none;
		background-color: #449d44;
		border-color: #398439;
	}
	.orange { color: orange; }
	.green { color: darkgreen; }
	.red { color: darkred; }
</style>
<div class="shop-item-compare">
	<h1><?= Html::encode($model->title) ?></h1>
	<h2><?= Html::encode($model->vendor_code . ' [ ' . $model->price . ' ]') ?></h2>
	<?php echo Html::a('Следующий', ['compare', 'id' => $model->getNextId()], ['class' => 'btn btn-next']); ?>
	<?php if ($product): ?>
		<?php if ($providerItem): ?>
			<?php echo Html::a('Привязать', ['assign', 'id' => $model->id, 'productId' => $product->id], ['class' => 'btn btn-assign']); ?>
			<div style="display: inline-block; width: 49%; vertical-align: top;">
				<?php
				if ($product->images) {
					foreach ($product->images as $image) {
						echo Html::img(AppHelper::uploadsPath() . '/' . CatalogProduct::FOLDER . '/' . $image->image, [
							'height' => 200,
						]);
					}
				}
				?>
			</div>
			<div style="display: inline-block; width: 49%; vertical-align: top;">
				Код: <b><?php echo $providerItem->code; ?></b><br/>
				<?php
				$info = $providerItem->getInfo();
				if (empty($info)) {
					echo 'Нет дополнительной информации';
				} else {
					foreach ($info as $key => $value) {
						if ($key == 'images') {

						} else {

						}
					}
					$images = $info['images'] ?? [];
					foreach ($images as $image) {
						echo Html::img('https://tdvega.com/' . $image, [
							'height' => 200,
						]);
					}
					echo '<br/>';
					echo 'Название: <b>' . ($info['title'] ?? null) . '</b><br/>';

					echo 'Производитель: <b>' . ($info['manufacturer'] ?? null) . '</b><br/>';
					echo 'Артикул: <b>' . ($info['article'] ?? null) . '</b><br/>';
					echo 'Внутренний код: <b>' . ($info['external_id'] ?? null) . '</b><br/>';
					echo 'Вес: <b>' . ($info['weight'] ?? null) . '</b><br/>';
					echo 'Габариты: <b>' . ($info['size'] ?? null) . '</b><br/>';
					echo 'Описание: <b>' . ($info['description'] ?? null) . '</b><br/>';
					$usage = $info['usage'] ?? [];
					if ($usage) {
						echo 'Справочники:<br/>';
						foreach ($usage as $item) {
							$link = Html::a($item['title'] ?? null, 'https://tdvega.com' . ($item['link'] ?? null), ['target' => '_blank']);
							echo '<hr/>' . implode(' / ', [
								$item['brand'] ?? null,
								$item['model'] ?? null,
								$item['manual'] ?? null,
								$link,
							]) . '<br/>';
						}
					}
					$codes = $info['codes'] ?? [];
					if ($codes) {
						/* @var $manualProducts ManualProduct[] */
						$manualProducts = ManualProduct::findAll(['code' => $codes]);
						if ($manualProducts) {
							echo '<br/><b>Найдены товары в справочниках</b><br/>';
							foreach ($manualProducts as $manualProduct) {
								echo $manualProduct->title . ' [<b>' . $manualProduct->code . '</b>]: ';
								$manualCategory = $manualProduct->manualCategory;
								if ($manualCategory) {
									$manual = $manualCategory->manual;
									if ($manual) {
										echo $manual->title . ' -> <i>' . $manualCategory->title . '</i><br/>';
										if ($manualProduct->product_id) {
											if ($manualProduct->product_id != $product->id) {
												echo '<b class="red">Привязан другой товар!</b>';
											} else {
												echo '<b class="green">Привязан текущий товар!</b>';
											}
										} else {
											echo '<b class="orange">Товар не привязан!</b>';
										}
									} else {
										echo 'некорректная связь со справочником';
									}
								} else {
									echo 'некорректная связь со страницей справочника';
								}
								echo '<hr/>';
							}
						} else {
							echo '<b>Не найдены товары из справочников.</b>';
						}
					}
				}
				?>
			</div>
		<?php else: ?>
			Должен быть привязан товар поставщика.
		<?php endif; ?>
	<?php else: ?>
	Товар не найден на сайте.
	<?php endif; ?>
</div>