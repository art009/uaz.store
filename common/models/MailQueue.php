<?php

namespace common\models;

use Yii;
use yii\web\View;

/**
 * This is the model class for table "mail_queue".
 *
 * @property integer $id
 * @property integer $status
 * @property string $to
 * @property string $subject
 * @property string $text
 */
class MailQueue extends \yii\db\ActiveRecord
{
	const STATUS_NEW = 0;
	const STATUS_SENDING = 1;
	const STATUS_SENT = 2;

	/**
	 * @var array
	 */
	static $statusList = [
		self::STATUS_NEW => 'Ожидает',
		self::STATUS_SENDING => 'Отправляется',
		self::STATUS_SENT => 'Отправлено',
	];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['text'], 'string'],
            [['to', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'to' => 'Кому',
            'subject' => 'Тема',
            'text' => 'Текст',
        ];
    }

	/**
	 * Создание письма для отправки
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $viewName
	 * @param array $params
	 *
	 * @return bool
	 */
	public static function create($to, $subject, $viewName, $params = [])
	{
		$mail = new self();
		$mail->status = self::STATUS_NEW;
		$mail->to = $to;
		$mail->subject = $subject;
		/* @var $view View */
		$view = Yii::createObject(['class' => View::className()]);
		$mail->text = $view->render('@common/mail/views/' . $viewName, $params, $mail);

		return $mail->save();
	}

	/**
	 * Отправка всех писем
	 *
	 * @param bool $all
	 *
	 * @return bool|int
	 */
	public static function send($all = true)
	{
		$result = 0;
		/* @var $mail self */
		$mail = self::find()->where(['status' => self::STATUS_NEW])->one();
		if ($mail) {
			$result = $mail->execute();
			if ($all) {
				$result += self::send();
			}
		}

		return $result;
	}

	/**
	 * Отправка письма
	 *
	 * @return int
	 */
	public function execute()
	{
		$result = 0;
		$this->updateAttributes(['status' => self::STATUS_SENDING]);
		try {
			$result = (int)Yii::$app->mailer->compose()
				->setTo($this->to)
				->setFrom([Yii::$app->params['fromEmail'] => Yii::$app->params['fromName']])
				->setSubject($this->subject)
				->setHtmlBody($this->text)
				->send();
		} catch (\Exception $exception) {
			Yii::info($exception->getMessage());
		}
		$this->updateAttributes(['status' => $result ? self::STATUS_SENT : self::STATUS_NEW]);

		return $result;
	}

	/**
	 * Возвращает название статуса
	 *
	 * @return mixed|null
	 */
	public function getStatusLabel()
	{
		return array_key_exists($this->status, self::$statusList) ? self::$statusList[$this->status] : null;
	}
}
