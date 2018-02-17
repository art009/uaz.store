<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notice".
 *
 * @property string $id
 * @property integer $type
 * @property integer $status
 * @property integer $user_id
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class Notice extends \yii\db\ActiveRecord
{
	const TYPE_NONE = 0;
	const TYPE_CALLBACK = 1;
	const TYPE_QUESTION = 2;
	const TYPE_ORDER = 3;

	const STATUS_NEW = 0;
	const STATUS_VIEW = 1;
	const STATUS_DONE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'user_id'], 'integer'],
            [['data'], 'string'],
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
            'type' => 'Тип',
            'status' => 'Статус',
            'user_id' => 'Пользователь',
            'data' => 'Данные',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * @inheritdoc
     * @return NoticeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NoticeQuery(get_called_class());
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
	 * Создание уведомления
	 *
	 * @param string $data
	 * @param int $type
	 *
	 * @return bool
	 */
    public static function create($data, $type = self::TYPE_NONE)
    {
    	$model = new self();
    	$model->type = $type;
    	$model->status = self::STATUS_NEW;
    	$model->data = $data;

    	return $model->save();
    }

	/**
	 * Возвращает данные уведомления
	 *
	 * @return mixed
	 */
    public function getData()
    {
    	return @json_decode($this->data, true);
    }
}
