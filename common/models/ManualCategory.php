<?php

namespace common\models;

use common\components\AppHelper;
use common\components\ImageHandler;
use Yii;

/**
 * This is the model class for table "manual_category".
 *
 * @property integer $id
 * @property integer $manual_id
 * @property integer $parent_id
 * @property integer $catalog_category_id
 * @property string $title
 * @property string $link
 * @property integer $hide
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogCategory $catalogCategory
 * @property ManualCategory $parent
 * @property Manual $manual
 * @property ManualCategory[] $manualCategories
 * @property ManualProduct[] $manualProducts
 */
class ManualCategory extends \yii\db\ActiveRecord
{
	const CATEGORY_TREE_CACHE_TAG = 'manual-category-tree-tag';

	const FOLDER = 'catalog-manual-page';
	const FOLDER_MEDIUM = self::FOLDER . '/m';

	const MEDIUM_IMAGE_WIDTH = 186;
	const MEDIUM_IMAGE_HEIGHT = 124;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manual_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manual_id', 'parent_id', 'catalog_category_id', 'hide'], 'integer'],
	        [['meta_keywords', 'meta_description'], 'string'],
            [['title', 'link'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'link', 'image'], 'string', 'max' => 255],
            ['link', 'unique', 'targetAttribute' => ['manual_id', 'link']],
            [['catalog_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategory::className(), 'targetAttribute' => ['catalog_category_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManualCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['manual_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manual::className(), 'targetAttribute' => ['manual_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manual_id' => 'ID справочника',
            'parent_id' => 'ID родительской категории',
            'catalog_category_id' => 'ID категории каталога',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'hide' => 'Скрывать?',
	        'image' => 'Картинка',
	        'meta_keywords' => 'Текст метатега keywords',
	        'meta_description' => 'Текст метатега description',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogCategory()
    {
        return $this->hasOne(CatalogCategory::className(), ['id' => 'catalog_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ManualCategory::className(), ['id' => 'parent_id']);
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getManual()
	{
		return $this->hasOne(Manual::className(), ['id' => 'manual_id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManualCategories()
    {
        return $this->hasMany(ManualCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManualProducts()
    {
        return $this->hasMany(ManualProduct::className(), ['manual_category_id' => 'id']);
    }


	/**
	 * Deletes model image
	 */
	protected function deleteImages()
	{
		if ($this->image) {
			$uploadsFolder = AppHelper::uploadsFolder();
			@unlink($uploadsFolder . '/' . self::FOLDER . '/' . $this->image);
		}
	}

	/**
	 * Сохранение картинки
	 *
	 * @param string $file
	 * @param string $hashName
	 */
	public function saveImage($file, $hashName = null)
	{
		if (file_exists($file)) {
			$uploadsFolder = AppHelper::uploadsFolder();

			$this->deleteImages();
			$this->image = $hashName ?: basename($file);

			$imageHandler = new ImageHandler();
			$imageHandler
				->load($file)
				->save($uploadsFolder . '/' . self::FOLDER . '/' . $this->image)
				->reload()
				->resizeCanvas(self::MEDIUM_IMAGE_WIDTH, self::MEDIUM_IMAGE_HEIGHT)
				->save($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $this->image);
		}
	}

	/**
	 * Возвращает путь до картинки
	 *
	 * @param bool $small
	 *
	 * @return null|string
	 */
	public function getImagePath($small = false)
	{
		if ($this->image && file_exists(AppHelper::uploadsFolder() . '/' . ($small ? self::FOLDER_MEDIUM : self::FOLDER) . '/' . $this->image)) {
			return AppHelper::uploadsPath() . '/' . ($small ? self::FOLDER_MEDIUM : self::FOLDER) . '/' . $this->image;
		} else {
			return null;
		}
	}

	/**
	 * @param bool $small
	 *
	 * @return null|string
	 */
	public function getClosestImage($small = true)
	{
		if ($this->image) {
			return $this->getImagePath($small);
		} else {
			/** @var ManualCategory $category */
			$category = $this->getManualCategories()->one();
			if ($this->parent_id) {
				return $category->getImagePath($small);
			} else {
				return $category->getClosestImage($small);
			}
		}
	}

	/**
	 * Построение хлебных крошек
	 *
	 * @return array
	 */
	public function createBreadcrumbs()
	{
		$result = [];
		$manual = $this->manual;
		$link = '/manual';
		$result[] = ['label' => 'Справочники', 'url' => ['/manual']];
		if ($manual) {
			$link .= '/' . $manual->link;
			$result[] = ['label' => $manual->title, 'url' => [$link]];
			$parent = $this->parent;
			if ($parent) {
				$grandParent = $parent->parent;
				if ($grandParent) {
					$link .= '/' . $grandParent->link;
					$result[] = ['label' => $grandParent->title, 'url' => [$link]];
				}
				$link .= '/' . $parent->link;
				$result[] = ['label' => $parent->title, 'url' => [$link]];
			}
		}

		$result[] = $this->title;

		return $result;
	}

	/**
	 * Полная ссылка
	 *
	 * @return string
	 */
	public function getFullLink()
	{
		$result = '/manual';
		if ($this->manual) {
			$result .= '/' . $this->manual->link;
			if ($this->parent) {
				if ($this->parent->parent) {
					$result .= '/' . $this->parent->parent->link;
				}
				$result .= '/' . $this->parent->link;
			}
		}
		$result .= '/' . $this->link;

		return $result;
	}
}
