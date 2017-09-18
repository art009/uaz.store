<?php

namespace common\models;

use Yii;

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
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['manual_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManualCategory::className(), 'targetAttribute' => ['manual_category_id' => 'id']],
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
}
