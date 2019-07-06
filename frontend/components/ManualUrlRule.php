<?php

namespace frontend\components;

use common\models\Manual;
use common\models\ManualCategory;

/**
 * Class ManualUrlRule
 *
 * @package frontend\components
 */
class ManualUrlRule extends \yii\base\BaseObject implements \yii\web\UrlRuleInterface
{
    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');
        $pathParts = explode("/", $pathInfo);

        if (count($pathParts) > 1 && $pathParts[0] == 'manual') {
            $manualLink = $pathParts[1];
            $categoryLink = count($pathParts) > 2 ? end($pathParts) : null;
	        $manual = $manualLink ? Manual::findOne(['link' => $manualLink]) : null;
            if ($manual) {
				$category = $categoryLink ? ManualCategory::findOne(['manual_id' => $manual->id, 'link' => $categoryLink]) : null;
				if ($category) {
					if ($category->image) {
						return ['manual/image', ['id' => $manual->id, 'categoryId' => $category->id]];
					} else {
						return ['manual/category', ['id' => $manual->id, 'categoryId' => $category->id]];
					}
				} else {
					return ['manual/view', ['id' => $manual->id]];
				}
	        }
        }

        return false;
    }
}
