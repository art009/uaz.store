<?php

namespace backend\models;

use Yii;
use yii\bootstrap\Html;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use common\components\AppHelper;
use common\components\ImageHandler;

/**
 * Class CatalogManual
 *
 * @property UploadedFile $imageFile
 *
 * @package backend\models
 */
class CatalogManual extends \common\models\CatalogManual
{
	/**
	 * @var UploadedFile
	 */
	public $imageFile;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return parent::rules() + [
			[['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'imageFile' => 'Загружаемая картинка',
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function load($data, $formName = null)
	{
		$result = parent::load($data, $formName = null);
		$this->imageFile = UploadedFile::getInstance($this, 'imageFile');

		return $result;
	}

	/**
	 * Deletes model image
	 */
	protected function deleteImages()
	{
		if ($this->image) {
			$uploadsFolder = AppHelper::uploadsFolder();
			@unlink($uploadsFolder . '/' . self::FOLDER . '/' . $this->image);
			@unlink($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $this->image);
		}
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		$result = true;
		if (parent::beforeSave($insert)) {
			if ($this->imageFile) {
				$name = md5(time()) . '.' . $this->imageFile->extension;
				$uploadsFolder = AppHelper::uploadsFolder();
				if ($this->imageFile->saveAs($uploadsFolder . '/' . self::FOLDER . '/' . $name)) {
					$this->deleteImages();
					$this->image = $name;

					/* @var $imageHandler ImageHandler */
					$imageHandler = Yii::$app->ih;
					$imageHandler
						->load($uploadsFolder . '/' . self::FOLDER . '/' . $name)
						->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
						->save($uploadsFolder . '/' . self::FOLDER . '/' . $name)
						->reload()
						->resizeCanvas(self::MEDIUM_IMAGE_WIDTH, self::MEDIUM_IMAGE_HEIGHT)
						->save($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $name);
				} else {
					$this->addError('imageFile', 'Директория недоступна для записи: ' . $uploadsFolder . '/' . self::FOLDER . '/');
					$result = false;
				}
			}
		}

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		if (parent::beforeDelete()) {

			if ($this->pages) {
				throw new BadRequestHttpException('Невозможно удалить непустой справочник');
			}

			$this->deleteImages();

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Справочники в виде списка
	 *
	 * @return array
	 */
	public static function getListed()
	{
		return ArrayHelper::map(self::find()->all(), 'id', 'title');
	}

	/**
	 * @param int|null $parentId
	 * @param CatalogCategory[] $categories
	 *
	 * @return array
	 */
	public static function categoryNodes($parentId = null, $categories = [])
	{
		$result = [];

		if (empty($categories)) {
			$categories = CatalogCategory::find()->all();
		}
		foreach ($categories as $category) {
			if ($category->parent_id == $parentId) {
				$item = [
					'id' => $category->id,
					'text' => Html::tag('span', $category->title, ['id' => 'node' . $category->id]),
					'href' => '#node' . $category->id,
					'selectable' => true,
				];
				$children = self::categoryNodes($category->id, $categories);
				if (!empty($children)) {
					$item['nodes'] = $children;
				}

				$result[] = $item;
			}
		}

		return $result;
	}

	/**
	 * Обработка дерева
	 *
	 * @param array $nodes
	 * @param int[] $existedIds
	 */
	protected function processCategoryNodes(&$nodes, $existedIds)
	{
		foreach ($nodes as &$node) {
			if (!in_array($node['id'], $existedIds)) {
				$node['tags'] = [
					Html::a('добавить', ['/catalog-manual-page/create', 'cid' => $node['id'], 'mid' => $this->id], ['class' => 'btn btn-xs btn-success'])
				];
			} else {
				$node['tags'] = [
					Html::a('удалить', ['/catalog-manual-page/delete', 'cid' => $node['id'], 'mid' => $this->id], [
						'class' => 'btn btn-xs btn-danger',
						'aria-label' => 'Удалить',
						'data-method' => 'post',
						'data-confirm' => 'Вы уверены, что хотите удалить эту страницу?',
					]),
					Html::a('редактировать', ['/catalog-manual-page/update', 'cid' => $node['id'], 'mid' => $this->id], ['class' => 'btn btn-xs btn-primary']),
					Html::a('просмотреть', ['/catalog-manual-page/view', 'cid' => $node['id'], 'mid' => $this->id], ['class' => 'btn btn-xs btn-info']),
				];
			}
			if (array_key_exists('nodes', $node)) {
				$node['text'] .= ' [' . count($node['nodes']) . '] ';
				$this->processCategoryNodes($node['nodes'], $existedIds);
			}
		}
	}

	/**
	 * Возвращение дерева категорий
	 *
	 * @return array
	 */
	public function getCategoryTree()
	{
		$nodes = self::categoryNodes();
		$existedIds = $this->getCategories()->select('category_id')->column();
		$this->processCategoryNodes($nodes, $existedIds);

		return $nodes;
	}
}
