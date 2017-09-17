<?php

namespace frontend\components;

use common\models\CatalogCategory;
use common\models\CatalogProduct;
use common\models\Manual;
use common\models\ManualCategory;

/**
 * Class CatalogUrlRule
 *
 * @package frontend\components
 */
class CatalogUrlRule extends \yii\base\Object implements \yii\web\UrlRuleInterface
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

        if (count($pathParts) > 1 && $pathParts[0] == 'catalog') {
	        $productLink = null;
        	if (count($pathParts) == 5) {
		        $productLink = end($pathParts);
	        }
	        $categoryLink = end($pathParts);
            $category = $categoryLink ? CatalogCategory::findOne(['link' => $categoryLink]) : null;
            $product = $productLink ? CatalogProduct::findOne(['link' => $productLink]) : null;
            if ($category) {
            	if ($product) {
		            return ['catalog/product', ['id' => $product->id, 'categoryId' => $category->id]];
	            } elseif ($category->children) {
		            return ['catalog/index', ['id' => $category->id]];
	            } else {
		            return ['catalog/view', ['id' => $category->id]];
	            }
	        }
        }

        return false;
    }
}
