<?php
namespace frontend\models;

use common\models\MailQueue;
use common\models\Notice;
use yii\base\Model;
use JsonSerializable;
use Yii;

/**
 * Class CallbackForm
 *
 * @package frontend\models
 */
class CallbackForm extends Model implements JsonSerializable
{
	const CACHE_DURATION = 600;

	/**
	 * @var string
	 */
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
		if (\Yii::$app->cache->exists($this->getCacheKey())) {
			$this->addError('phone', 'Вы недавно оставляли заявку! Попробуйте позже.');
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
		\Yii::$app->cache->add($this->getCacheKey(), 1);

		//MailQueue::create('support@uaz.store', 'Заказ обратного звонка', 'callback', ['phone' => $this->phone]);
		return Notice::create(json_encode($this), Notice::TYPE_CALLBACK);
	}

	/**
	 * Возвращает ключ для кеша
	 *
	 * @return string
	 */
	public function getCacheKey()
	{
		return 'Callback' . preg_replace('/[^0-9]/', '', $this->phone);
	}
}
