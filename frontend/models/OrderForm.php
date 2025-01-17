<?php
namespace frontend\models;

use common\models\Notice;
use JsonSerializable;
use yii\base\Model;

/**
 * Class OrderForm
 *
 * @package frontend\models
 */
class OrderForm extends Model implements JsonSerializable
{
	const CACHE_DURATION = 600;

	/**
	 * @var string
	 */
	public $phone;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $text;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['phone', 'text'], 'required'],
			['phone', 'trim'],
			['name', 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'phone' => 'Телефон',
			'name' => 'Имя',
			'text' => 'Запрос',
		];
	}

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        $timeToOpenForm = \Yii::$app->session->get($this->getSessionTimeKey(), 0);
        if (($timeToOpenForm > 0) && ($timeToOpenForm > time())) {
            $waitFor = $timeToOpenForm - time();
            $this->addError('phone', 'Вы недавно оставляли заявку! Попробуйте через '.\Yii::$app->formatter->asDuration($waitFor));
        } else if (\Yii::$app->cache->exists($this->getCacheKey())) {
            $this->addError('phone', 'Вы недавно оставляли заявку! Попробуйте позже.');
        } else {
            $counterOfRequest = \Yii::$app->session->get($this->getSessionKey(), 0);
            if ($counterOfRequest >= \Yii::$app->params['maxOrderAttempts']) {
                $timeToOpenForm = time() + \Yii::$app->params['delayBetweenOrderAttempts'];
                \Yii::$app->session->set($this->getSessionTimeKey(), $timeToOpenForm);
                $this->addError('phone', 'Вы недавно оставляли заявку! Попробуйте через '.\Yii::$app->formatter->asDuration(\Yii::$app->params['delayBetweenOrderAttempts']));
            }
            \Yii::$app->session->set($this->getSessionKey(), $counterOfRequest + 1);
        }

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
			'name' => $this->name,
			'text' => $this->text,
		];
	}

	/**
	 * Создание письма для администратора
	 *
	 * @return bool
	 */
	public function create()
	{
		if (!$this->validate()) {
			return false;
		}
		\Yii::$app->cache->add($this->getCacheKey(), 1, self::CACHE_DURATION);

		return Notice::create(json_encode($this), Notice::TYPE_ORDER);
	}

	/**
	 * Возвращает ключ для кеша
	 *
	 * @return string
	 */
	public function getCacheKey()
	{
		return 'Order' . preg_replace('/[^0-9]/', '', $this->phone);
	}

    public function getSessionKey()
    {
        return 'order';
    }

    public function getSessionTimeKey()
    {
        return 'orderTime';
    }
}
