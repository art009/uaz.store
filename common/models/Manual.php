<?php

namespace common\models;

use Yii;
use common\components\AppHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "manual".
 *
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property string $image
 * @property string $description
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $year
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ManualCategory[] $categories
 */
class Manual extends \yii\db\ActiveRecord
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
        return 'manual';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'link'], 'required'],
            [['description', 'meta_keywords', 'meta_description'], 'string'],
            [['year', 'hide'], 'integer'],
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
            'description' => 'Описание',
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
        return $this->hasMany(ManualCategory::className(), ['manual_id' => 'id']);
    }

	/**
	 * Возвращает путь до картинки
	 *
	 * @return null|string
	 */
	public function getImagePath()
	{
		if ($this->image && file_exists(AppHelper::uploadsFolder() . '/' . self::FOLDER_MEDIUM . '/' . $this->image)) {
			return AppHelper::uploadsPath() . '/' . self::FOLDER_MEDIUM . '/' . $this->image;
		} else {
			return null;
		}
	}
}