<?php
namespace frontend\models;

use common\models\MailQueue;
use yii\base\Model;

/**
 * Class CallbackForm
 *
 * @package frontend\models
 */
class CallbackForm extends Model
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
	 * Создание письма для администратора
	 *
	 * @return bool
	 */
    public function callback()
    {
        if (!$this->validate()) {
            return false;
        }
        
        return MailQueue::create('support@uaz.store', 'Заказ обратного звонка', 'callback', ['phone' => $this->phone]);
    }
}
