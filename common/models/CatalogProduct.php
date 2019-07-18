<?php

namespace common\models;

use backend\models\ManualProductSearch;
use common\components\AppHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

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
 * @property ManualProduct[] $manuals
 * @property CatalogProduct[] $similarProducts
 * @property CatalogProduct[] $relatedProducts
 *
 * @property integer $categoriesCount
 * @property integer $hasCategories
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

    private $manualCategories = [];

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
            [
                [
                    'category_id',
                    'hide',
                    'on_main',
                    'cart_counter',
                    'length',
                    'width',
                    'height',
                    'weight',
                    'rest',
                    'oversize'
                ],
                'integer'
            ],
            [['title', 'link'], 'required'],
            [['meta_keywords', 'meta_description', 'description'], 'string'],
            [['price', 'price_to', 'price_old'], 'number'],
            [['created_at', 'updated_at', 'external_id'], 'safe'],
            [
                [
                    'title',
                    'link',
                    'image',
                    'shop_title',
                    'provider_title',
                    'shop_code',
                    'provider_code',
                    'manufacturer_code',
                    'provider',
                    'manufacturer',
                    'unit'
                ],
                'string',
                'max' => 255
            ],
            [['link'], 'unique'],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CatalogCategory::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
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
            'hasCategories' => 'Есть категории',
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
     * @return \yii\db\ActiveQuery
     */
    public function getSimilarProducts()
    {
        return $this->hasMany(CatalogProduct::className(), ['id' => 'similar_product_id'])
            ->viaTable('catalog_product_similar', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProducts()
    {
        return $this->hasMany(CatalogProduct::className(), ['id' => 'related_product_id'])
            ->viaTable('catalog_product_related', ['product_id' => 'id']);
    }

    public function getInternalRelatedProducts()
    {
        $similarProducts = $this->similarProducts;
        $tmp = ManualProductToCatalogProduct::find()
            ->where(['catalog_product_id' => $this->id])
            ->indexBy('manual_product_id')->all();
        $manualProductIds = array_keys($tmp);
        $finalCatalogProducts = [];
        $tmp = ManualProduct::find()
            ->where(['in', 'id', $manualProductIds])
            ->indexBy('manual_category_id')
            ->all();
        $manualCategoryIds = array_keys($tmp);
        foreach ($manualCategoryIds as $manualCategoryId) {
            $manualCategory = ManualCategory::findOne($manualCategoryId);
            if ($manualCategory) {
                /**
                 * @var $manualProducts ManualProduct[]
                 */
                $manualProducts = $manualCategory->manualProducts;
                foreach ($manualProducts as $manualProduct) {
                    $catalogProducts = $manualProduct->catalogProducts;
                    foreach ($catalogProducts as $catalogProduct) {
                        if ($catalogProduct->id == $this->id) {
                            continue;
                        }
                        foreach ($similarProducts as $similarProduct) {
                            if ($this->id == $similarProduct->id) {
                                continue;
                            }
                        }
                        $finalCatalogProducts[$catalogProduct->id] = $catalogProduct;
                    }
                }
            }
        }

        return $finalCatalogProducts;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function setSimilarProducts(array $productIds, $delete = true)
    {
        if ($delete) {
            CatalogProductSimilar::deleteAll('product_id = :id', [':id' => $this->id]);
            CatalogProductSimilar::deleteAll('similar_product_id = :id', [':id' => $this->id]);
        }
        foreach ($productIds as $productId) {
            if ($this->id == $productId) {
                continue;
            }
            try {
                $catalogProductSimilar = new CatalogProductSimilar();
                $catalogProductSimilar->product_id = $this->id;
                $catalogProductSimilar->similar_product_id = $productId;
                $catalogProductSimilar->save();
            } catch (\Exception $e) {
                //do nothing cause it's because something goes wrong at console job. Ufff
            }
            try {
                $catalogProductSimilar = new CatalogProductSimilar();
                $catalogProductSimilar->product_id = $productId;
                $catalogProductSimilar->similar_product_id = $this->id;
                $catalogProductSimilar->save();
            } catch (\Exception $e) {
                //do nothing cause it's because something goes wrong at console job. Ufff
            }
        }
    }

    public function setRelatedProducts(array $productIds, $delete = true)
    {
        if ($delete) {
            CatalogProductRelated::deleteAll('product_id = :id', [':id' => $this->id]);
            CatalogProductRelated::deleteAll('related_product_id = :id', [':id' => $this->id]);
        }
        foreach ($productIds as $productId) {
            if ($this->id == $productId) {
                continue;
            }
            try {
                $catalogProductRelated = new CatalogProductRelated();
                $catalogProductRelated->product_id = $this->id;
                $catalogProductRelated->related_product_id = $productId;
                $catalogProductRelated->save();
            } catch (\Exception $e) {
                //do nothing cause it's because something goes wrong at console job. Ufff
            }
            try {
                $catalogProductRelated = new CatalogProductRelated();
                $catalogProductRelated->product_id = $productId;
                $catalogProductRelated->related_product_id = $this->id;
                $catalogProductRelated->save();
            } catch (\Exception $e) {
                //do nothing cause it's because something goes wrong at console job. Ufff
            }
        }
    }

    public function addRelatedProducts($products, $delete = true)
    {
        foreach ($products as $product) {
            if ($delete) {
                CatalogProductRelated::deleteAll('product_id = :id and related_product_id = :related_product_id',
                    [':id' => $this->id, ':related_product_id' => $product->id]
                );
                CatalogProductRelated::deleteAll('product_id = :id and related_product_id = :related_product_id',
                    [':id' => $product->id, ':related_product_id' => $this->id]
                );
            }
            if ($this->id == $product->id) {
                continue;
            }
            try {
                $catalogProductRelated = new CatalogProductRelated();
                $catalogProductRelated->product_id = $this->id;
                $catalogProductRelated->related_product_id = $product->id;
                $catalogProductRelated->save();
            } catch (\Exception $e) {
                //do nothing cause it's because something goes wrong at console job. Ufff
            }
            try {
                $catalogProductRelated = new CatalogProductRelated();
                $catalogProductRelated->product_id = $product->id;
                $catalogProductRelated->related_product_id = $this->id;
                $catalogProductRelated->save();
            } catch (\Exception $e) {
                //do nothing cause it's because something goes wrong at console job. Ufff
            }
        }
    }

    public function getManuals()
    {
        $manualProductIds = ManualProductToCatalogProduct::find()->where(['catalog_product_id' => $this->id])->indexBy('manual_product_id')->all();
        $manualProductIds = array_keys($manualProductIds);
        return ManualProduct::find()->where(['in', 'id', $manualProductIds])->all();
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

    public function getCategoriesCount()
    {
        return sizeof($this->categories);
    }

    public function getHasCategories()
    {
        return $this->categoriesCount > 0;
    }

    public function getHasCategoriesLabel()
    {
        return AppHelper::$yesNoList[(int)$this->hasCategories];
    }

    public function updateCategories()
    {
        $categoriesIds = [];
        $manualProducts = $this->manualProducts;
        /**
         * @var ManualProduct[] $manualProducts
         */
        foreach ($manualProducts as $manualProduct) {
            $manualCategory = $manualProduct->manualCategory;
            $this->manualCategories[] = $manualCategory;
            $this->collectParents($manualCategory);
            $categoriesIds = ArrayHelper::merge($categoriesIds, $this->collectCategories($this->manualCategories));
        }
        $currentCategories = $this->categories;
        foreach ($currentCategories as $category) {
            $categoriesIds[] = $category->id;
        }
        $uniqueCategories = array_unique($categoriesIds);
        CatalogProductToCategory::deleteAll(['product_id' => $this->id]);
        foreach ($uniqueCategories as $uniqueCategory) {
            $cptc = new CatalogProductToCategory();
            $cptc->product_id = $this->id;
            $cptc->category_id = $uniqueCategory;
            $cptc->save();
        }
    }

    private function collectParents($manualCategory)
    {
        if ($manualCategory->parent) {
            $this->manualCategories[] = $manualCategory->parent;
            $this->collectParents($manualCategory->parent);
        }
    }

    private function collectCategories($manualCategories)
    {
        $categoriesIds = [];
        foreach ($manualCategories as $manualCategory) {
            if ($manualCategory && $manualCategory->catalog_category_id) {
                $categoriesIds[] = $manualCategory->catalog_category_id;
            }
        }
        return $categoriesIds;
    }
}
