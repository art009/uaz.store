<?php

namespace frontend\components;

use common\models\CatalogCategory;
use common\models\CatalogManual;

/**
 * Class ManualUrlRule
 *
 * @package frontend\components
 */
class ManualUrlRule extends \yii\base\Object implements \yii\web\UrlRuleInterface
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
	        $manual = $manualLink ? CatalogManual::findOne(['link' => $manualLink]) : null;
            if ($manual) {
				$category = $categoryLink ? CatalogCategory::findOne(['link' => $categoryLink]) : null;
				if ($category) {
					return ['manual/view', ['id' => $manual->id, 'categoryId' => $category->id]];
				} else {
					return ['manual/view', ['id' => $manual->id]];
				}
	        }
        }

        return false;
    }
}
