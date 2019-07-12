<?php


namespace console\controllers;

use common\models\CatalogProduct;
use common\models\Manual;
use common\models\ManualProduct;
use common\models\ManualProductToCatalogProduct;
use yii\console\Controller;
use yii\helpers\BaseConsole;

class ProductController extends Controller
{
    public function actionFillSimilarProducts()
    {
        $catalogProducts = CatalogProduct::find();
        $catalogProductsSimilar = [];
        foreach ($catalogProducts->each() as $catalogProduct) {
            $manualProductToCatalogProducts = ManualProductToCatalogProduct::find()->where(['catalog_product_id' => $catalogProduct->id])->all();
            $manualProductIds = [];
            foreach ($manualProductToCatalogProducts as $manualProductToCatalogProduct) {
                $manualProductIds[] = $manualProductToCatalogProduct->manual_product_id;
            }
            $manualProducts = ManualProduct::find()->where(['in', 'id', $manualProductIds])->all();
            $products = [];
            foreach ($manualProducts as $manualProduct) {
                $products = $manualProduct->catalogProducts;
            }
            foreach ($products as $product) {
                if (!isset($catalogProductsSimilar[$catalogProduct->id])) {
                    $catalogProductsSimilar[$catalogProduct->id] = [];
                }
                if ($catalogProduct->id != $product->id) {
                    $catalogProductsSimilar[$catalogProduct->id][] =  $product->id;
                    $catalogProductsSimilar[$product->id][] =  $catalogProduct->id;
                    BaseConsole::output("Build items for {$catalogProduct->id}");
                }
            }
        }
        foreach ($catalogProductsSimilar as $catalogProductId => $items) {
            $items = array_unique($items);
            CatalogProduct::findOne($catalogProductId)->setSimilarProducts($items, false);
            BaseConsole::output("Assigned items for {$catalogProductId}: ".implode(", ", $items));
        }
    }
}