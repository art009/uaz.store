<?php

use backend\models\Order;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $order Order */

$this->title = 'Подтверждение возврата по заказу №' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Все заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Заказ №' . $order->id, 'url' => ['view', 'id' => $order->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group">
		<?php echo Html::label('Пароль для подтверждения', 'password'); ?>
		<?php echo Html::passwordInput('password', null, [
			'class' => 'form-control',
			'autocomplete' => 'return-password',
		]); ?>
	</div>

	<div class="form-group">
		<?= Html::submitButton('Подтвердить', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
