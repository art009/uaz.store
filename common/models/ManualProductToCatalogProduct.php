<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "manual_product_to_catalog_product".
 *
 * @property int $manual_product_id ID товара справочника
 * @property int $catalog_product_id ID товара каталога
 *
 * @property CatalogProduct $catalogProduct
 * @property ManualProduct $manualProduct
 */
class ManualProductToCatalogProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manual_product_to_catalog_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manual_product_id', 'catalog_product_id'], 'integer'],
            [['manual_product_id', 'catalog_product_id'], 'unique', 'targetAttribute' => ['manual_product_id', 'catalog_product_id']],
            [['catalog_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['catalog_product_id' => 'id']],
            [['manual_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManualProduct::className(), 'targetAttribute' => ['manual_product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'manual_product_id' => 'Manual Product ID',
            'catalog_product_id' => 'Catalog Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'catalog_product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManualProduct()
    {
        return $this->hasOne(ManualProduct::className(), ['id' => 'manual_product_id']);
    }
}
