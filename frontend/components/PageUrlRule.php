<?php

namespace frontend\components;

use common\models\Page;

/**
 * Class PageUrlRule
 *
 * @package frontend\components
 */
class PageUrlRule extends \yii\base\BaseObject implements \yii\web\UrlRuleInterface
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

        if (count($pathParts) == 1) {
	        $link = end($pathParts);
            $page = $link ? Page::findOne(['link' => $link]) : null;
            if ($page) {
            	return ['page/view', ['id' => $page->id]];
	        }
        }

        return false;
    }
}
