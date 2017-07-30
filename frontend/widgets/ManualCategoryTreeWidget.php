<?php

namespace frontend\widgets;

use common\components\AppHelper;
use common\models\ManualCategory;
use yii\caching\TagDependency;

/**
 * Class ManualCategoryTreeWidget
 *
 * @package frontend\widgets
 */
class ManualCategoryTreeWidget extends CategoryTreeWidget
{
	/**
	 * @var int
	 */
	public $manualId;

	/**
	 * @return array
	 */
	protected function getCategories()
	{
		$categories = ManualCategory::getDb()->cache(function(){
			return ManualCategory::find()
				->select(['id', 'parent_id', 'title', 'link'])
				->where([
					'hide' => AppHelper::NO,
					'manual_id' => $this->manualId,
				])
				->orderBy('parent_id ASC, title ASC')
				->asArray()
				->all();
		}, 0, new TagDependency(['tags' => ManualCategory::CATEGORY_TREE_CACHE_TAG . $this->manualId]));

		return $categories;
	}
}
