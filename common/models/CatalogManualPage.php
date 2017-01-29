<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "catalog_manual_page".
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
class CatalogManualPage extends \yii\db\ActiveRecord
{
	const FOLDER = 'catalog-manual-page';
	const FOLDER_MEDIUM = self::FOLDER . '/m';

	const MEDIUM_IMAGE_WIDTH = 100;
	const MEDIUM_IMAGE_HEIGHT = 100;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_manual_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manual_id', 'category_id'], 'required'],
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
            'manual_id' => 'Справочник',
            'category_id' => 'Категория',
            'image' => 'Картинка',
            'meta_keywords' => 'Текст метатега keywords',
            'meta_description' => 'Текст метатега description',
            'description' => 'Текст для страницы',
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
