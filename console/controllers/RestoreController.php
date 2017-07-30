<?php

namespace console\controllers;

use backend\models\Menu;
use backend\models\Manual;
use common\components\AppHelper;
use common\models\ManualCategory;
use common\models\ManualProduct;
use Yii;
use yii\console\Controller;

/**
 * Class RestoreController
 *
 * @package console\controllers
 */
class RestoreController extends Controller
{
	/**
	 * @return void
	 */
	public function actionIndex()
	{
		echo 'Контроллер для восстановления базового состояния компонентов приложения' . PHP_EOL;
	}

	/**
	 * Переустановка основного меню
	 *
	 * @param bool $force
	 */
	public function actionMenu($force = false)
	{
		$items = [
			[
				'title' => 'Товары',
				'link' => '/catalog',
				'controller_id' => 'catalog',
				'action_id' => 'index',
			],
			[
				'title' => 'О компании',
				'link' => '/about',
				'controller_id' => 'site',
				'action_id' => 'about',
			],
			[
				'title' => 'Оплата и доставка',
				'link' => '/delivery',
				'controller_id' => 'site',
				'action_id' => 'delivery',
			],
			[
				'title' => 'Отзывы',
				'link' => '/reviews',
				'controller_id' => 'reviews',
				'action_id' => null,
			],
		];

		$count = Menu::find()->count();
		if ($force || $count == 0) {
			Menu::deleteAll();

			foreach ($items as $item) {
				$menu = new Menu();
				$menu->setAttributes($item);
				$menu->save();
			}
		}
	}

	/**
	 * Переустановка списка каталогов
	 *
	 * @param bool $force
	 */
	public function actionManualList($force = false)
	{
		$count = Manual::find()->count();
		if ($force || $count == 0) {
			$path = \Yii::$app->basePath . "/data/manual/";
			if (file_exists($path . "index.json")) {
				$response = @file_get_contents($path . "index.json");
				$list = json_decode($response);
				if (json_last_error() == JSON_ERROR_NONE) {
					$models = $list->models ?? [];
					if ($models) {
						foreach ($models as $model) {
							$manual = new Manual();
							$manual->title = $model->name ?? null;
							$manual->link = $model->link ?? null;
							$manual->description = $model->description ?? null;
							if ($manual->validate()) {
								$image = $path . $model->image;
								if (file_exists($image)) {
									$name = md5(time() . pathinfo($image, PATHINFO_BASENAME)) . '.' . pathinfo($image,
											PATHINFO_EXTENSION);
									$manual->saveImage($image, $name);
									$manual->save();
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Загрузка товаров и картинок для справочников
	 */
	public function actionManualProduct()
	{
		$path = Yii::$app->basePath . "/data/manual/";
		$manualLinks = [2206, 31512, 31519, 3160, 3163, 39629];
		//$manualLinks = [31512];
		foreach ($manualLinks as $manualLink) {
			$manual = Manual::findOne(['link' => $manualLink]);
			if ($manual && file_exists($path . $manual->link . '/index.json')) {
				AppHelper::$transliterationLinks = [];
				$categories = $this->loadFromJsonFile($path . $manual->link . '/index.json');
				foreach ($categories as $category) {
					$manualCategory = $this->createManualCategory($manual, $category['title']);
					if ($manualCategory) {
						$subCategories = $category['items'] ?? [];
						foreach ($subCategories as $subCategory) {
							$manualSubCategory = $this->createManualCategory($manual, $subCategory['title'], $manualCategory->id);
							if ($manualSubCategory) {
								$productCategories = $subCategory['items'] ?? [];
								foreach ($productCategories as $productCategory) {
									$manualProductCategory = $this->createManualCategory($manual, $productCategory['title'], $manualSubCategory->id);
									if ($manualProductCategory) {
										if (!$manualProductCategory->image) {
											$image = $path . $manual->link . '/' . $productCategory['id'] . '.png';
											if (file_exists($image)) {
												$name = md5($manualProductCategory->id . time() . pathinfo($image, PATHINFO_BASENAME)) . '.' . pathinfo($image, PATHINFO_EXTENSION);
												$manualProductCategory->saveImage($image, $name);
												$manualProductCategory->update(false, ['image']);
											} else {
												echo 'Нет картинки категории - ' . $productCategory['title'] . PHP_EOL;
											}
										}
										if (!$manualProductCategory->getManualProducts()->count()) {
											if (file_exists($path . $manual->link . '/' . $productCategory['id'] . '.json')) {
												$items = $this->loadFromJsonFile($path . $manual->link . '/' . $productCategory['id'] . '.json');
												foreach ($items as $item) {
													$manualProduct = $this->createManualProduct($manualProductCategory->id, $item);
													if (!$manualProduct) {
														echo 'Не удалось создать товар - ' . $item['number'] . PHP_EOL;
													}
												}
											} else {
												echo 'Нет товаров для категории - ' . $productCategory['title'] . ' [' . $productCategory['id'] . ']'. PHP_EOL;
											}
										} else {
											echo 'Товыра уже есть - ' . $productCategory['title'] . ' [' . $productCategory['id'] . ']'. PHP_EOL;
										}
									} else {
										echo 'Не удалось создать категорию 3 уровня - ' . $productCategory['title'] . PHP_EOL;
									}
								}
							} else {
								echo 'Не удалось создать категорию 2 уровня - ' . $subCategory['title'] . PHP_EOL;
							}
						}
					} else {
						echo 'Не удалось создать категорию - ' . $category['title'] . PHP_EOL;
					}
				}
			} else {
				echo 'Справочник ' . $manualLink . ' не найден' . PHP_EOL;
			}
		}
	}

	/**
	 * @param Manual $manual
	 * @param string $title
	 * @param int|null $parentId
	 *
	 * @return ManualCategory|null
	 */
	protected function createManualCategory(Manual $manual, $title, $parentId = null)
	{
		$link = AppHelper::transliteration($title);
		$result = ManualCategory::findOne([
			'manual_id' => $manual->id,
			'link' => $link,
		]);
		if (empty($result)) {
			$category = new ManualCategory();
			$category->manual_id = $manual->id;
			$category->parent_id = $parentId;
			$category->title = $title;
			$category->link = $link;
			$category->meta_keywords = $manual->link . ' ' . mb_strtolower($title, 'utf-8');
			$category->meta_description =  $title . ' - раздел каталога ' . $manual->link;
			if ($category->save()) {
				$result = $category;
			} else {
				echo print_r($category->errors, true) . PHP_EOL;
			}
		}

		return $result;
	}

	/**
	 * @param $categoryId
	 * @param $data
	 *
	 * @return ManualProduct|null
	 */
	protected function createManualProduct(int $categoryId, array $data)
	{
		$result = null;
		$number = $data['number'] ?? null;
		if ($number && $categoryId) {
			$product = new ManualProduct();
			$product->manual_category_id = $categoryId;
			$product->number = $data['position'] ?? null;
			$product->code = $number;
			$product->title = $data['title'] ?? null;
			$product->left = $data['left'] ?? null;
			$product->top = $data['top'] ?? null;
			$product->width = $data['width'] ?? null;
			$product->height = $data['height'] ?? null;
			$product->positions = json_encode($data['positions'] ?? []);
			if ($product->save()) {
				$result = $product;
			}
		}

		return $result;
	}

	/**
	 * Загрузка данных из JSON-файла
	 *
	 * @param string $path
	 *
	 * @return mixed|null
	 */
	protected function loadFromJsonFile($path)
	{
		$result = null;
		if (file_exists($path)) {
			$content = @file_get_contents($path);
			$json = json_decode($content, true);
			if (json_last_error() == JSON_ERROR_NONE) {
				$result = $json;
			}
		}

		return $result;
	}

	/**
	 * @param $path
	 * @param $data
	 *
	 * @return bool|int
	 */
	protected function saveToJsonFile($path, $data)
	{
		return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
	}
}
