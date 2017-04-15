<?php

namespace frontend\components;

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

        if (count($pathParts) == 2 && $pathParts[0] == 'manual') {
            $link = $pathParts[1];
            if ($link && $manual = CatalogManual::findOne(['link' => $link])) {
                return ['manual/view', ['id' => $manual->id]];
            }
        }

        return false;
    }
}
