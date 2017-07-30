<?php
/* @var $this yii\web\View */
/* @var $item \common\classes\TreeItem */

use yii\helpers\Html;

if ($item) {
	echo Html::beginTag('li', [
		'class' => ($item->active ? 'active ' : null) . ($item->expanded ? 'expanded' : null)
	]);
	echo $item->link ? Html::a($item->title, $item->link, ['class' => $item->toggleable ? 'toggle-area' : '']) : $item->title;
	if ($item->items) {
		echo Html::tag('span', '', ['class' => 'toggle-area']);
		echo Html::beginTag('ul');
		foreach ($item->items as $item) {
			echo $this->render('_category-tree-item', ['item' => $item]);
		}
		echo Html::endTag('ul');
	}
	echo Html::endTag('li');
}
