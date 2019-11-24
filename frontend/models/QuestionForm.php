<?php
namespace frontend\models;

use common\models\Notice;
use JsonSerializable;
use yii\base\Model;

/**
 * Class QuestionForm
 *
 * @package frontend\models
 */
class QuestionForm extends Model implements JsonSerializable
{
	const CACHE_DURATION = 600;

	/**
	 * @var string
	 */
	public $email;

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
			[['email', 'text'], 'required'],
			['email', 'trim'],
			['email', 'email'],
			['name', 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => 'E-mail',
			'name' => 'Имя',
			'text' => 'Вопрос',
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
            $this->addError('email', 'Вы недавно задавали вопрос! Попробуйте через '.\Yii::$app->formatter->asDuration($waitFor));
        } else if (\Yii::$app->cache->exists($this->getCacheKey())) {
            $this->addError('email', 'Вы недавно задавали вопрос! Попробуйте позже.');
        } else {
            $counterOfRequest = \Yii::$app->session->get($this->getSessionKey(), 0);
            if ($counterOfRequest >= \Yii::$app->params['maxQuestionAttempts']) {
                $timeToOpenForm = time() + \Yii::$app->params['delayBetweenQuestionAttempts'];
                \Yii::$app->session->set($this->getSessionTimeKey(), $timeToOpenForm);
                $this->addError('email', 'Вы недавно задавали вопрос! Попробуйте через '.\Yii::$app->formatter->asDuration(\Yii::$app->params['delayBetweenCallbackAttempts']));
            }
            \Yii::$app->session->set($this->getSessionKey(), $counterOfRequest + 1);
        }

        return parent::afterValidate();
    }

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'email' => $this->email,
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

		return Notice::create(json_encode($this), Notice::TYPE_QUESTION);
	}

	/**
	 * Возвращает ключ для кеша
	 *
	 * @return string
	 */
	public function getCacheKey()
	{
		return 'Question' . $this->email;
	}

    public function getSessionKey()
    {
        return 'question';
    }

    public function getSessionTimeKey()
    {
        return 'questionTime';
    }
}
