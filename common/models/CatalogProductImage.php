<?php

namespace common\models;

use common\components\AppHelper;
use Yii;

/**
 * This is the model class for table "catalog_product_image".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $image
 * @property integer $main
 *
 * @property CatalogProduct $product
 */
class CatalogProductImage extends \yii\db\ActiveRecord
{
    const MAIN_NO = 0;
    const MAIN_YES = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'main'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'ID родительского товара',
            'image' => 'Картинка',
            'main' => 'Главная?',
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
     * @inheritdoc
     * @return CatalogProductImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CatalogProductImageQuery(get_called_class());
    }

	public function getImagePath($small = true)
	{
		return AppHelper::getImagePath($this->image, $small ? CatalogProduct::FOLDER_MEDIUM : CatalogProduct::FOLDER);
	}
}
