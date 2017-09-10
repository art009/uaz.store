<?php

use yii\helpers\Html;
use common\components\AppHelper;
use backend\models\CatalogProduct;

/* @var $this yii\web\View */
/* @var $model \app\modules\pms\models\ShopItem */
/* @var $providerItem \app\modules\pms\models\ProviderItem */

$product = $model->product;
$providerItem = $model->getProviderItems()->one();
?>
<div class="shop-item-view">
	<h1>Сравнени информации для товара "<?= Html::encode($model->title) ?>"</h1>
	<?php if ($product): ?>
		<?php if ($providerItem): ?>
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
							$link = Html::a($item['title'] ?? null,'https://tdvega.com/' . ($item['link'] ?? null), ['target' => '_blank']);
							echo '<hr/>' . implode(' / ', [
								$item['brand'] ?? null,
								$item['model'] ?? null,
								$item['manual'] ?? null,
								$link,
							]) . '<br/>';
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