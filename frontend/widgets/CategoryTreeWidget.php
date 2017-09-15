<?php

namespace frontend\widgets;

use common\classes\TreeItem;
use common\components\AppHelper;
use common\models\CatalogCategory;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class CategoryTreeWidget
 *
 * Виджет категорий
 *
 * @package frontend\widgets
 */
class CategoryTreeWidget extends Widget
{
	/**
	 * @var string
	 */
	public $baseLink = '';

	/**
	 * @var
	 */
	public $categoryId = null;

	/**
	 * @var bool
	 */
	public $toggleableParent = false;

	/**
	 * @var TreeItem[]
	 */
	protected $items = [];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if (empty($this->items)) {
			$categories = $this->getCategories();
			$this->buildTree($categories);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('category-tree', [
			'items' => $this->items,
		]);
	}

	/**
	 * @return array
	 */
	protected function getCategories()
	{
		$categories = CatalogCategory::getDb()->cache(function(){
			return CatalogCategory::find()
				->select(['id', 'parent_id', 'title', 'link'])
				->where([
					'hide' => AppHelper::NO,
				])
				->orderBy('parent_id ASC, title ASC')
				->asArray()
				->all();
		}, 0, new TagDependency(['tags' => CatalogCategory::CATEGORY_TREE_CACHE_TAG]));

		return $categories;
	}

	/**
	 * @param $categories
	 */
	protected function buildTree($categories)
	{
		if ($categories) {
			foreach ($categories as $category) {
				$item = new TreeItem($category);
				if ($item->isValid()) {
					$item->active = ($item->id == $this->categoryId);
					$item->expanded = $item->active;
					if ($item->parentId) {
						$this->addChildItem($this->items, $item);
					} else {
						$item->updateLink($this->baseLink);
						$this->items[$item->id] = $item;
					}
				}
			}
		}
	}

	/**
	 * @param TreeItem[] $items
	 * @param TreeItem $child
	 *
	 * @return bool
	 */
	protected function addChildItem($items, $child)
	{
		foreach ($items as $id => $item) {
			if ($item->id == $child->parentId) {
				$item->addChild($child);
				$item->toggleable = $this->toggleableParent;
				return $item->expanded;
			} elseif ($item->items) {
				$item->expanded |= $this->addChildItem($item->items, $child);
			}
		}

		return false;
	}
}
