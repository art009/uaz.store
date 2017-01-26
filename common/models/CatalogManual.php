<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "catalog_manual".
 *
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $description
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogManualCategory[] $catalogManualCategories
 */
class CatalogManual extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_manual';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'link'], 'required'],
            [['meta_keywords', 'meta_description', 'description'], 'string'],
            [['hide'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'link', 'image'], 'string', 'max' => 255],
            [['link'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
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
    public function getCatalogManualCategories()
    {
        return $this->hasMany(CatalogManualCategory::className(), ['manual_id' => 'id']);
    }
}
