<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $link
 * @property string $description
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $hide
 *
 * @property Page $parent
 * @property Page[] $pages
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'hide'], 'integer'],
            [['title', 'link'], 'required'],
            [['description', 'meta_keywords', 'meta_description'], 'string'],
            [['title', 'link'], 'string', 'max' => 255],
            [['link'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'ID родительской страницы',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'description' => 'Текст',
            'meta_keywords' => 'Текст метатега keywords',
            'meta_description' => 'Текст метатега description',
            'hide' => 'Скрывать?',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['parent_id' => 'id']);
    }
}
