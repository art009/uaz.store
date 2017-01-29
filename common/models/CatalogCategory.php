<?php

namespace common\models;

use common\components\AppHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use common\components\ImageHandler;

/**
 * This is the model class for table "catalog_category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $link
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 * @property UploadedFile $imageFile
 *
 * @property CatalogCategory $parent
 * @property CatalogCategory[] $children
 * @property CatalogProduct[] $products
 */
class CatalogCategory extends \yii\db\ActiveRecord
{
    const FOLDER = 'catalog-category';
    const FOLDER_SMALL = self::FOLDER . '/s';
    const FOLDER_MEDIUM = self::FOLDER . '/m';

    const SMALL_IMAGE_WIDTH = 40;
    const SMALL_IMAGE_HEIGHT = 40;

    const MEDIUM_IMAGE_WIDTH = 100;
    const MEDIUM_IMAGE_HEIGHT = 100;

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'hide'], 'integer'],
            [['title', 'link'], 'required'],
            [['meta_keywords', 'meta_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'link', 'image'], 'string', 'max' => 255],
            [['link'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родительская категория',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'image' => 'Картинка',
            'meta_keywords' => 'Текст метатега keywords',
            'meta_description' => 'Текст метатега description',
            'hide' => 'Скрывать?',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
            'imageFile' => 'Загружаемая картинка',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(CatalogCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(CatalogCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(CatalogProduct::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CatalogCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CatalogCategoryQuery(get_called_class());
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
            @unlink($uploadsFolder . '/' . self::FOLDER_SMALL . '/' . $this->image);
            @unlink($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $this->image);
        }
    }

    /**
     * Категории в виде списка
     *
     * @return array
     */
    public static function getListed()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'title');
    }

    /**
     * Все родительские категории в виде списка
     *
     * @param bool|false $reverse Обратный порядок
     *
     * @return array
     */
    public function getParentsList($reverse = false)
    {
        $result = [];
        $parent = $this->parent;
        while ($parent) {
            $result[$parent->id] = $parent->title;
            $parent = $parent->parent;
        }

        return $reverse ? array_reverse($result, true) : $result;
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
                        ->save($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $name)
                        ->reload()
                        ->resizeCanvas(self::SMALL_IMAGE_WIDTH, self::SMALL_IMAGE_HEIGHT)
                        ->save($uploadsFolder . '/' . self::FOLDER_SMALL . '/' . $name);
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

            if ($this->children) {
                throw new BadRequestHttpException('Невозможно удалить категорию с подкатегориями');
            }
            if ($this->products) {
                throw new BadRequestHttpException('Невозможно удалить категорию с товарами');
            }

            $this->deleteImages();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int|null $parentId
     * @param string|null $prefix
     * @param int $excludeId
     *
     * @return array
     */
    public static function getTreeView($parentId = null, $prefix = null, $excludeId = 0)
    {
        $result = [];
        /* @var $categories CatalogCategory[] */
        $categories = self::find()
            ->where(['parent_id' => $parentId])
            ->andWhere(['<>', 'id', $excludeId])
            ->all();

        if ($categories) {
            foreach ($categories as $category) {
                $result[$category->id] = $prefix . $category->title;
                $result += self::getTreeView($category->id, $result[$category->id] . ' / ', $excludeId);
            }
        }

        return $result;
    }
}
