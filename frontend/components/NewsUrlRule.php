<?php

namespace frontend\components;

use common\models\News;

/**
 * Class NewsUrlRule
 *
 * @package frontend\components
 */
class NewsUrlRule extends \yii\base\BaseObject implements \yii\web\UrlRuleInterface
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
     * @throws \yii\base\InvalidConfigException
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');
        $pathParts = explode("/", $pathInfo);

        if (count($pathParts) > 0 && $pathParts[0] === 'news') {
            if (count($pathParts) === 2) {
                $model = News::findOne(['id' => $pathParts[1]]);
                if ($model) {
                    return ['news/view', ['id' => $model->id]];
                }
            }
        }

        return false;
    }
}
