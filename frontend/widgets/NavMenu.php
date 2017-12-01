<?php

namespace frontend\widgets;

use common\components\AppHelper;
use common\models\Menu;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\caching\TagDependency;

/**
 * Class NavMenu
 *
 * @package frontend\widgets
 */
class NavMenu extends Nav
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		Html::addCssClass($this->options, ['navbar-nav', 'navbar-left']);

		$controllerId = Yii::$app->controller->id;
		$actionId = Yii::$app->controller->action->id;

		/* @var $menuItems Menu[] */
		$menuItems = Menu::getDb()->cache(function(){
			return Menu::find()
				->where(['hide' => AppHelper::NO])
				->orderBy('sort_order')
				->all();
		}, 0, new TagDependency(['tags' => Menu::CACHE_TAG]));

		foreach ($menuItems as $menuItem) {
			$item = [
				'label' => $menuItem->title,
				'url' => [$menuItem->link],
				'active' => Yii::$app->request->getPathInfo() == trim($menuItem->link, '/'),
			];
			if ($menuItem->controller_id) {
				$item['active'] = ($controllerId == $menuItem->controller_id && (!$menuItem->action_id || $actionId == $menuItem->action_id));
			}
			$this->items[] = $item;
		}
	}
}
