<?php

namespace console\controllers;

use common\models\CatalogCategory;
use common\models\CatalogProduct;
use common\models\Page;
use SitemapPHP\Sitemap;
use yii\console\Controller;
use yii\helpers\Url;
use Yii;

/**
 * Class SitemapController
 * @package console\controllers
 */
class SitemapController extends Controller
{
	/**
	 * @return void
	 */
	public function actionIndex()
	{
		echo 'First, configure options "baseUrl" & "sitemapPriority" in params-local.php' . PHP_EOL;
		echo 'Run generator with command: "php yii sitemap/generate"' . PHP_EOL;
	}

    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
	public function actionGenerate()
	{
	    $priorityConfig = Yii::$app->params['sitemapPriority'] ?? [];
	    $mainPriority = $priorityConfig['main'] ?? '1.0';
	    $pagePriority = $priorityConfig['page'] ?? '1.0';
	    $categoryPriority = $priorityConfig['category'] ?? '2.0';
	    $productPriority = $priorityConfig['product'] ?? '3.0';

	    $baseUrl = Yii::$app->params['baseUrl'] ?? 'http://'.gethostname();

        Yii::setAlias('@webroot', Yii::getAlias('@frontend').DIRECTORY_SEPARATOR.'web');
	    $urlManager = Yii::$app->urlManager;
	    $urlManager->setHostInfo($baseUrl);
	    $urlManager->setScriptUrl('/');
	    $urlManager->enablePrettyUrl = true;

        $sitemap = new Sitemap($urlManager->getHostInfo());
        $sitemap->setPath(Yii::getAlias('@webroot').DIRECTORY_SEPARATOR);

        $sitemap->addItem('/', $mainPriority, 'daily', 'Today');

        $this->generatePages($sitemap, $pagePriority);

        $sitemap->addItem('/catalog', $categoryPriority, 'daily', 'Today');
        $this->generateCategories($sitemap, $categoryPriority, $productPriority);

        $sitemap->addItem('/search', $pagePriority, 'daily', 'Today');
        $sitemap->addItem('/login', $pagePriority, 'daily', 'Today');
        $sitemap->addItem('/signup', $pagePriority, 'daily', 'Today');
        $sitemap->addItem('/cart', $pagePriority, 'daily', 'Today');

        $sitemap->createSitemapIndex($urlManager->getHostInfo().$urlManager->getScriptUrl(), 'Today');
	}

    /**
     * @param Sitemap $sitemap
     * @param string $categoryPriority
     * @param string $productPriority
     * @return void
     */
	protected function generateCategories(Sitemap $sitemap, $categoryPriority, $productPriority)
    {
        $categories = CatalogCategory::findAll(['parent_id' => null]);
        /** @var \common\models\CatalogCategory $category */
        foreach ($categories as $category) {
            $this->generateCategory($sitemap, $category, $categoryPriority, $productPriority);
        }
    }

    /**
     * @param Sitemap $sitemap
     * @param CatalogCategory $category
     * @param string $categoryPriority
     * @param string $productPriority
     * @return void
     */
	protected function generateCategory(Sitemap $sitemap, CatalogCategory $category, $categoryPriority, $productPriority)
    {
        $sitemap->addItem(Url::to('/catalog'.$category->getFullLink()), $categoryPriority, 'daily', 'Today');

        if (count($category->children)) {
            /** @var \common\models\CatalogCategory $category */
            foreach ($category->children as $childCategory) {
                $this->generateCategory($sitemap, $childCategory, $categoryPriority, $productPriority);
            }
        } else {
            $this->generateProducts($sitemap, $category, $productPriority);
        }
    }

    /**
     * @param Sitemap $sitemap
     * @param CatalogCategory $category
     * @param string $productPriority
     * @return void
     */
    protected function generateProducts(Sitemap $sitemap, CatalogCategory $category, $productPriority)
    {
        $products = $category->getFrontProducts();
        /** @var \common\models\CatalogProduct $product */
        foreach ($products as $product) {
            $sitemap->addItem(Url::to($product->getFullLink()), $productPriority, 'daily', 'Today');
        }
    }

    /**
     * @param Sitemap $sitemap
     * @param string $pagePriority
     * @return void
     */
    protected function generatePages(Sitemap $sitemap, $pagePriority)
    {
        $pages = Page::findAll(['hide' => 0]);
        /** @var \common\models\Page $page */
        foreach ($pages as $page) {
            $sitemap->addItem(Url::to('/'.$page->link), $pagePriority, 'daily', 'Today');
        }
    }
}
