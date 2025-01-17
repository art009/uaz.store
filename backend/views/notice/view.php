<?php

use backend\models\Notice;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Notice */

$this->title = $model->getTypeName() . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$data = $model->getData();
?>
<div class="notice-view">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if ($model->status != Notice::STATUS_DONE): ?>
    <p>
        <?= Html::a('Обрабатано', ['done', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
	<?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
	        [
        	    'attribute' => 'status',
		        'value' => $model->getStatusName(),
		    ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

	<?php if ($model->type == Notice::TYPE_CALLBACK): ?>
		<p>
			Необходимо перезвонить по телефону:<br/>
            <?php if (isset($data['name'])): ?>
                Имя: <b><?php echo $data['name'] ?? null; ?></b><br>
            <?php endif; ?>
                Телефон: <b><a href="tel:<?php echo $data['phone'] ?? null; ?>"><?php echo $data['phone'] ?? null; ?></b></a>
		</p>
	<?php endif; ?>

	<?php if ($model->type == Notice::TYPE_QUESTION): ?>
		<p>
			Поступил вопрос от <b><?php echo $data['name'] ?? 'Неизвестного'; ?></b> <a href="mailto:<?php echo $data['email'] ?? null; ?>"><?php echo $data['email'] ?? null; ?></a>:<br/>
			<?php echo $data['text'] ?? ''; ?>
		</p>
	<?php endif; ?>

	<?php if ($model->type == Notice::TYPE_ORDER): ?>
		<p>
			Поступил запрос от <b><?php echo $data['name'] ?? 'Неизвестного'; ?></b> <a href="tel:<?php echo $data['phone'] ?? null; ?>"><?php echo $data['phone'] ?? null; ?></a>:<br/>
			<?php echo $data['text'] ?? ''; ?>
		</p>
	<?php endif; ?>

</div>
