<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "catalog_manual_category".
 *
 * @property integer $id
 * @property integer $manual_id
 * @property integer $category_id
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $description
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogCategory $category
 * @property CatalogManual $manual
 */
class CatalogManualCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_manual_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manual_id', 'category_id', 'hide'], 'integer'],
            [['meta_keywords', 'meta_description', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['manual_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogManual::className(), 'targetAttribute' => ['manual_id' => 'id']],
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
            'category_id' => 'ID категории',
            'image' => 'Картинка',
            'meta_keywords' => 'Текст метатега keywords',
            'meta_description' => 'Текст метатега description',
            'description' => 'Текст для справочника',
            'hide' => 'Скрывать?',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CatalogCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManual()
    {
        return $this->hasOne(CatalogManual::className(), ['id' => 'manual_id']);
    }
}
