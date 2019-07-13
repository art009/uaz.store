<?php

namespace common\models;

use backend\models\ManualProductSearch;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "manual_product".
 *
 * @property integer $id
 * @property integer $manual_category_id
 * @property integer $product_id
 * @property string $number
 * @property string $code
 * @property string $title
 * @property integer $left
 * @property integer $top
 * @property integer $width
 * @property integer $height
 * @property string $positions
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogProduct $product
 * @property ManualCategory $manualCategory
 * @property CatalogProduct[] $catalogProducts
 */
class ManualProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manual_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manual_category_id', 'product_id', 'left', 'top', 'width', 'height', 'hide'], 'integer'],
            [['created_at', 'updated_at', 'positions'], 'safe'],
            [['number', 'code', 'title'], 'string', 'max' => 255],
            [
                ['product_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => CatalogProduct::className(),
                'targetAttribute' => ['product_id' => 'id']
            ],
            [
                ['manual_category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ManualCategory::className(),
                'targetAttribute' => ['manual_category_id' => 'id']
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
            'manual_category_id' => 'ID страницы справочника',
            'product_id' => 'ID товара каталога',
            'number' => 'Номер на чертеже',
            'code' => 'Артикул завода',
            'title' => 'Название',
            'left' => 'Отступ слева',
            'top' => 'Отступ сверху',
            'width' => 'Ширина',
            'height' => 'Высота',
            'positions' => 'Дополнительные позиции',
            'hide' => 'Скрывать?',
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
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManualCategory()
    {
        return $this->hasOne(ManualCategory::className(), ['id' => 'manual_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProducts()
    {
        return $this->hasMany(CatalogProduct::className(), ['id' => 'catalog_product_id'])
            ->viaTable('manual_product_to_catalog_product', ['manual_product_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getPositionsArray()
    {
        $result = [];
        if ($this->positions) {
            $json = json_decode($this->positions, true);
            if (is_array($json)) {
                $result = $json;
            }
        }

        return $result;
    }

    /**
     * @param bool $insert
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function beforeSave($insert)
    {
        $result = true;
        if (parent::beforeSave($insert)) {
            if ($this->getIsNewRecord() && !$this->left) {
                $this->left = 10;
                $this->top = 10;
                $this->width = 60;
                $this->height = 20;
            }
        }

        return $result;
    }

    public function saveRelated($catalogProduct)
    {
        $searchModel = new ManualProductSearch();
        $searchModel->manual_category_id = $this->manual_category_id;
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $manualProducts = $dataProvider->getModels();
        /**
         * @var ManualProduct $manualProduct
         */
        $productsToUpdate = [];
        foreach ($manualProducts as $manualProduct) {
            $currentCatalogProducts = $manualProduct->catalogProducts;
            foreach ($currentCatalogProducts as $currentCatalogProduct) {
                $tmp = ManualProductToCatalogProduct::find()->where(['catalog_product_id' => $currentCatalogProduct->id])->indexBy('manual_product_id')->all();
                $manualProductsIds = array_keys($tmp);
                foreach ($manualProductsIds as $manualProductsId) {
                    $catalogProductsIds = array_keys(ManualProductToCatalogProduct::find()->where(['manual_product_id' => $manualProductsId])->indexBy('catalog_product_id')->all());
                    foreach ($catalogProductsIds as $catalogProductsId) {
                        if (!isset($productsToUpdate[$catalogProduct->id])) {
                            $productsToUpdate[$catalogProductsId] = [$catalogProduct];
                        }
                        $productsToUpdate[$catalogProductsId][] = CatalogProduct::findOne($catalogProductsId);
                    }
                }
            }
        }
        foreach ($productsToUpdate as $catalogProductsId => $products) {
            $addTo = CatalogProduct::findOne($catalogProductsId);
            $addTo->addRelatedProducts($products);
        }
    }

    public function removeRelated(CatalogProduct $catalogProduct)
    {
        $searchModel = new ManualProductSearch();
        $searchModel->manual_category_id = $this->manual_category_id;
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $manualProducts = $dataProvider->getModels();
        /**
         * @var ManualProduct $manualProduct
         */
        $productsToUpdate = [];
        foreach ($manualProducts as $manualProduct) {
            $currentCatalogProducts = $manualProduct->catalogProducts;
            foreach ($currentCatalogProducts as $currentCatalogProduct) {
                $tmp = ManualProductToCatalogProduct::find()->where(['catalog_product_id' => $currentCatalogProduct->id])->indexBy('manual_product_id')->all();
                $manualProductsIds = array_keys($tmp);
                foreach ($manualProductsIds as $manualProductsId) {
                    $catalogProductsIds = array_keys(ManualProductToCatalogProduct::find()->where(['manual_product_id' => $manualProductsId])->indexBy('catalog_product_id')->all());
                    foreach ($catalogProductsIds as $catalogProductsId) {
                        if (!isset($productsToUpdate[$catalogProduct->id])) {
                            $productsToUpdate[$catalogProductsId] = [$catalogProduct];
                        }
                        $productsToUpdate[$catalogProductsId][] = CatalogProduct::findOne($catalogProductsId);
                    }
                }
            }
        }
        foreach ($productsToUpdate as $catalogProductsId => $products) {
            foreach ($products as $catalogProduct) {
                CatalogProductRelated::deleteAll('product_id = :id and related_product_id = :related_product_id',
                    [':id' => $this->id, ':related_product_id' => $catalogProduct->id]
                );
                CatalogProductRelated::deleteAll('product_id = :id and related_product_id = :related_product_id',
                    [':id' => $catalogProduct->id, ':related_product_id' => $this->id]
                );
            }
        }
    }
}
