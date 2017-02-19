<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Order */

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Все заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Заказы пользователя #' . $model->user_id, 'url' => ['index', 'userId' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <code>
        Создан/Изменен: <?php echo $model->created_at; ?> / <?php echo $model->updated_at; ?>
    </code>
    <h4>
        Статус: <b><?php echo $model->getStatusName(); ?></b> <i><?php echo $model->changed_at; ?></i>
    </h4>
    <h4>
        Стоимость: <b><?php echo $model->sum; ?> руб</b> + доставка <b><?php echo $model->delivery_sum; ?> руб</b>
    </h4>
	<?php /*$form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'delivery_type')->dropDownList($model::$deliveryList) ?>

	<?= $form->field($model, 'payment_type')->dropDownList($model::$paymentList) ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end();*/ ?>
</div>
