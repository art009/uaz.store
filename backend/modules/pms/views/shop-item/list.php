<?php

use yii\helpers\Html;
use yii\grid\GridView;

$bundle = \yii\bootstrap\BootstrapAsset::register($this);
$assetUrl = $bundle->baseUrl;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $provider \app\modules\pms\models\Provider */
?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web' . $assetUrl . '/css/bootstrap.min.css')?> ">
<script type="application/javascript">
	function receiveMessage(event)
	{
		var data = event.data;
		if (typeof data.goTo !== 'undefined') {
			var tr = document.querySelector('tr[data-key="' + data.goTo + '"]'),
				prev = document.querySelector('tr.success');

			if (prev) {
				prev.classList.remove('success');
			}

			tr.scrollIntoView();
			tr.classList.add('success');
		}
	}

	window.addEventListener("message", receiveMessage, false);

	window.onload = function() {
		window.opener.postMessage('ready', window.opener.location.href);
	};
</script>
<div class="shop-item-view">
	<h1>Прайс-лист поставщика "<?= Html::encode($provider->name) ?>"</h1>
	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'tableOptions' => [
			'class' => 'table table-striped table-bordered'
		],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'vendor_code',
			'title',
			'price',
			'unit',
		],
	]); ?>
</div>