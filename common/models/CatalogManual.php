<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "catalog_manual".
 *
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property string $image
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $year
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogManualPage[] $pages
 */
class CatalogManual extends \yii\db\ActiveRecord
{
	const FOLDER = 'catalog-manual';
	const FOLDER_MEDIUM = self::FOLDER . '/m';

	const MEDIUM_IMAGE_WIDTH = 186;
	const MEDIUM_IMAGE_HEIGHT = 124;

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
            [['meta_keywords', 'meta_description'], 'string'],
            [['hide', 'year'], 'integer'],
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
            'year' => 'Год выпуска',
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
    public function getCategories()
    {
        return $this->hasMany(CatalogManualPage::className(), ['manual_id' => 'id']);
    }
}
