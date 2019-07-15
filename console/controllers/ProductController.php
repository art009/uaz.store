<?php


namespace console\controllers;

use common\models\CatalogProduct;
use common\models\Manual;
use common\models\ManualProduct;
use common\models\ManualProductToCatalogProduct;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;

class ProductController extends Controller
{
    public function actionFillSimilarProducts()
    {
        Yii::$app->db->createCommand()->truncateTable('catalog_product_similar')->execute();
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

    public function actionFillRelatedProducts()
    {
        Yii::$app->db->createCommand()->truncateTable('catalog_product_related')->execute();
        $catalogProducts = CatalogProduct::find();
        $catalogProductsRelated = [];
        foreach ($catalogProducts->each() as $catalogProduct) {
            $products = $catalogProduct->getInternalRelatedProducts();
            foreach ($products as $product) {
                if ($catalogProduct->id == $product->id) {
                    continue;
                }
                if (!isset($catalogProductsRelated[$catalogProduct->id])) {
                    $catalogProductsRelated[$catalogProduct->id] = [];
                }
                if (!isset($catalogProductsRelated[$product->id])) {
                    $catalogProductsRelated[$product->id] = [];
                }
                if ($catalogProduct->id != $product->id) {
                    $catalogProductsRelated[$catalogProduct->id][] =  $product->id;
                    $catalogProductsRelated[$product->id][] =  $catalogProduct->id;
                }
            }
            BaseConsole::output("Built items for {$catalogProduct->id}");
        }
        foreach ($catalogProductsRelated as $catalogProductId => $items) {
            $items = array_unique($items);
            CatalogProduct::findOne($catalogProductId)->setRelatedProducts($items, false);
            BaseConsole::output("Assigned related items for {$catalogProductId}: ".implode(", ", $items));
        }
    }
}