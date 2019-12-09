<?php

namespace common\models;

use Yii;
use common\components\AppHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $description
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $hide
 * @property string $created_at
 * @property string $updated_at
 */
class News extends \yii\db\ActiveRecord
{
    const FOLDER = 'news';
    const FOLDER_SMALL = self::FOLDER . '/s';
    const FOLDER_MEDIUM = self::FOLDER . '/m';
    const SMALL_IMAGE_WIDTH = 100;
    const SMALL_IMAGE_HEIGHT = 100;
    const MEDIUM_IMAGE_WIDTH = 285;
    const MEDIUM_IMAGE_HEIGHT = 285;
    const MAX_HEIGHT = 720;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'meta_keywords', 'meta_description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
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
            'image' => 'Картинка',
            'description' => 'Текст',
            'meta_keywords' => 'Текст метатега keywords',
            'meta_description' => 'Текст метатега description',
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
     * @param bool $small
     * @return string
     */
    public function getImagePath($small = true)
    {
        $image = !empty($this->image) ? $this->image : ('img/empty-'.$small ? 's' : 'm').'.png';
        return AppHelper::getImagePath($image, $small ? self::FOLDER_MEDIUM : self::FOLDER);
    }
}
