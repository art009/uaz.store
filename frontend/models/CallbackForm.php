<?php
namespace frontend\models;

use common\models\MailQueue;
use common\models\Notice;
use yii\base\Model;
use JsonSerializable;

/**
 * Class CallbackForm
 *
 * @package frontend\models
 */
class CallbackForm extends Model implements JsonSerializable
{
    public $phone;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        ['phone', 'required'],
	        ['phone', 'trim'],
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'phone' => 'Телефон',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterValidate()
	{
		if (mb_strlen(preg_replace('/[^0-9]/', '', $this->phone)) < 11) {
			$this->addError('phone', 'Необходим 10-значный номер телефона.');
		}

		return parent::afterValidate();
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'phone' => $this->phone,
		];
	}

	/**
	 * Создание письма для администратора
	 *
	 * @return bool
	 */
    public function callback()
    {
        if (!$this->validate()) {
            return false;
        }

        //MailQueue::create('support@uaz.store', 'Заказ обратного звонка', 'callback', ['phone' => $this->phone]);
        return Notice::create(json_encode($this), Notice::TYPE_CALLBACK);
    }
}
