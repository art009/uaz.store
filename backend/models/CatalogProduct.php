<?php

namespace backend\models;

use common\components\AppHelper;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\web\UploadedFile;

/**
 * Class CatalogProduct
 *
 * @property UploadedFile[] $imageFiles
 *
 * @package backend\models
 */
class CatalogProduct extends \common\models\CatalogProduct
{
    const SMALL_IMAGE_WIDTH = 40;
    const SMALL_IMAGE_HEIGHT = 40;

    const MEDIUM_IMAGE_WIDTH = 100;
    const MEDIUM_IMAGE_HEIGHT = 100;

	/**
	 * Нужно ли сбросить кеш для виджета на главной
	 *
	 * @var bool
	 */
    protected $invalidateOnMainCache = false;

    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

	/**
	 * @var array
	 */
    public $category_ids = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$rules = parent::rules();
    	$rules[] = [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 5];
    	$rules[] = [['category_ids'], 'safe'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'imageFiles' => 'Загружаемые картинки',
            'images' => 'Загруженные картинки',
            'length' => 'Длина, мм',
            'width' => 'Ширина, мм',
            'height' => 'Высота, мм',
            'weight' => 'Вес, гр',
            'category_ids' => 'Категории',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName = null);
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            if ($images = $this->images) {
                foreach ($images as $image) {
                    $image->delete();
                }
            }

            return true;
        } else {
            return false;
        }
    }

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			if (($this->isAttributeChanged('hide') && $this->on_main == AppHelper::YES) ||
				$this->isAttributeChanged('on_main')
			) {
				$this->invalidateOnMainCache = true;
			}

			return true;
		}

		return false;
	}

    /**
     * @param bool $insert
     *
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->imageFiles) {
            foreach ($this->imageFiles as $num => $file) {
                $image = new CatalogProductImage();
                $image->num = $num;
                $image->file = $file;
                $image->product_id = $this->id;
                $image->save();
            }
            $this->updateImage();
        }

		if (!is_array($this->category_ids)) {
			$this->category_ids = [];
		}

		$actualCategoryIds = [];
		foreach ($this->categories as $category) {
			$actualCategoryIds[] = $category->id;
        	if (!in_array($category->id, $this->category_ids)) {
        		$this->unlink('categories', $category);
			}
		}

		$categories = CatalogCategory::findAll(array_diff($this->category_ids, $actualCategoryIds));
		foreach ($categories as $category) {
			$this->link('categories', $category);
		}

		if ($this->invalidateOnMainCache) {
			TagDependency::invalidate(Yii::$app->cache, self::ON_MAIN_CACHE_TAG);
		}
    }

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->category_ids = $this->getCategories()->select('id')->column();
	}

    /**
     * Обновление основной картинки
     */
    public function updateImage()
    {
        $this->image = null;

        $image = CatalogProductImage::find()
            ->where(['main' => CatalogProductImage::MAIN_YES, 'product_id' => $this->id])
            ->one();

        if ($image == null) {
            $image = $this->getImages()->one();
        }

        if ($image) {
            $image->updateAttributes(['main' => CatalogProductImage::MAIN_YES]);
            $this->image = $image->image;
        }

        $this->updateAttributes(['image' => $this->image]);
    }

    /**
     * @param string|null $separator
     *
     * @return string
     */
    public function getImagesHtml($separator = null)
    {
        $result = [];
        if ($this->images) {
            foreach ($this->images as $image) {
                $img = Html::img(AppHelper::uploadsPath() . '/' . self::FOLDER_MEDIUM . '/' . $image->image);
                $imgLink = Html::a($img, ['set-image', 'id' => $image->id], ['title' => 'Сделать главной', 'class' => 'set-image']);
                $deleteLink = Html::a(Html::icon('trash'), ['delete-image', 'id' => $image->id], ['title' => 'Удалить картинку', 'class' => 'delete-image']);
                $result[] = Html::tag('div', $imgLink . $deleteLink, [
                    'class' => 'product-image' . ($image->main == CatalogProductImage::MAIN_YES ? ' main' : ''),
                ]);
            }
        }

        return empty($result) ? null : implode($separator, $result);
    }
}
