<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $link
 * @property integer $hide
 * @property string $controller_id
 * @property string $action_id
 * @property integer $sort_order
 *
 * @property Menu $parent
 * @property Menu[] $menus
 */
class Menu extends \yii\db\ActiveRecord
{
	const CACHE_TAG = 'menu-model-cache-tag';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'hide', 'sort_order'], 'integer'],
            [['title', 'link'], 'required'],
            [['title', 'link', 'controller_id', 'action_id'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'ID родительского пункта',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'hide' => 'Скрывать?',
            'controller_id' => 'Контроллер',
            'action_id' => 'Действие',
            'sort_order' => 'Порядок сортировки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id']);
    }
}
