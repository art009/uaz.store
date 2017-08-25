<?php

namespace app\modules\pms\models;

use Yii;


/**
 * This is the model class for table "provider".
 *
 * @property integer $id
 * @property string $name
 * @property integer $deleted
 */
class Provider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'deleted' => 'Удалён',
        ];
    }
}