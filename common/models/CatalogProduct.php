<?php

namespace common\models;

use common\components\AppHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "catalog_product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $link
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $price
 * @property string $price_to
 * @property string $price_old
 * @property string $shop_title
 * @property string $provider_title
 * @property string $shop_code
 * @property string $provider_code
 * @property string $manufacturer_code
 * @property string $description
 * @property integer $hide
 * @property integer $on_main
 * @property string $provider
 * @property string $manufacturer
 * @property integer $cart_counter
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $weight
 * @property string $unit
 * @property integer $rest
 * @property string $external_id
 * @property integer $oversize
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogCategory[] $categories
 * @property CatalogProductImage[] $images
 * @property ManualProduct[] $manualProducts
 */
class CatalogProduct extends \yii\db\ActiveRecord
{
    const FOLDER = 'catalog-product';
    const FOLDER_SMALL = self::FOLDER . '/s';
    const FOLDER_MEDIUM = self::FOLDER . '/m';

	const SMALL_IMAGE_WIDTH = 88;
	const SMALL_IMAGE_HEIGHT = 88;

	const MEDIUM_IMAGE_WIDTH = 285;
	const MEDIUM_IMAGE_HEIGHT = 285;

	const MAX_HEIGHT = 720;

    const ON_MAIN_CACHE_TAG = 'catalog-product-on-main-tag';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'hide', 'on_main', 'cart_counter', 'length', 'width', 'height', 'weight', 'rest', 'oversize'], 'integer'],
            [['title', 'link'], 'required'],
            [['meta_keywords', 'meta_description', 'description'], 'string'],
            [['price', 'price_to', 'price_old'], 'number'],
            [['created_at', 'updated_at', 'external_id'], 'safe'],
            [['title', 'link', 'image', 'shop_title', 'provider_title', 'shop_code', 'provider_code', 'manufacturer_code', 'provider', 'manufacturer', 'unit'], 'string', 'max' => 255],
            [['link'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Родительская категория',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'image' => 'Главная картинка',
            'meta_keywords' => 'Текст метатега keywords',
            'meta_description' => 'Текст метатега description',
            'price' => 'Цена',
            'price_to' => 'Цена до',
            'price_old' => 'Старая цена',
            'shop_title' => 'Название в магазине',
            'provider_title' => 'Название у поставщика',
            'shop_code' => 'Артикул в магазине',
            'provider_code' => 'Артикул у поставщика',
            'manufacturer_code' => 'Артикул у производителя',
            'description' => 'Описание',
            'hide' => 'Скрывать?',
            'on_main' => 'На главной странице?',
            'provider' => 'Поставщик',
            'manufacturer' => 'Производитель',
            'cart_counter' => 'Счетчик добавлений в корзину',
            'length' => 'Длина',
            'width' => 'Ширина',
            'height' => 'Высота',
            'weight' => 'Вес',
            'unit' => 'Единица измерения',
            'rest' => 'Остаток',
            'external_id' => 'Код синхронизации',
            'oversize' => 'Крупногабаритный',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
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
    public function getCategories()
    {
		return $this->hasMany(CatalogCategory::className(), ['id' => 'category_id'])
			->viaTable('catalog_product_to_category', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(CatalogProductImage::className(), ['product_id' => 'id'])
	        ->orderBy('main DESC');
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getManualProducts()
	{
		return $this->hasMany(ManualProduct::className(), ['id' => 'manual_product_id'])
			->viaTable('manual_product_to_catalog_product', ['catalog_product_id' => 'id']);
	}

    /**
     * @inheritdoc
     * @return CatalogProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CatalogProductQuery(get_called_class());
    }

	/**
	 * Возвращает код для сайта
	 *
	 * @return string
	 */
    public function getCode()
    {
	    return str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

	/**
	 * Возвращает путь до картинки
	 *
	 * @param bool $small
	 *
	 * @return null|string
	 */
    public function getImagePath($small = true)
    {
    	if ($this->image) {
    		return AppHelper::getImagePath($this->image, $small ? self::FOLDER_MEDIUM : self::FOLDER);
	    } else {
    		return '/img/empty-s.png';
	    }
    }

	/**
	 * @return string
	 */
	public function getFullLink()
	{
		$result = '';
		if ($this->categories) {
			$result = '/catalog' . $this->categories[0]->getFullLink() . '/' . $this->link;
		}

		return $result;
	}

	/**
	 * @param int|null $categoryId
	 * @return array
	 */
	public function createBreadcrumbs(int $categoryId = null): array
	{
		$result = [];
		if ($this->categories) {
			$result = $this->categories[0]->createBreadcrumbs(true);
		}
		$result[] = $this->title;

		return $result;
	}

	/**
	 * Скрыт ли товар
	 *
	 * @return bool
	 */
	public function isHidden(): bool
	{
		return $this->hide;
	}
}
