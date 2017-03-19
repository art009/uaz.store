<?php

namespace backend\models;

use Yii;

/**
 * Class Notice
 *
 * @package backend\models
 */
class Notice extends \common\models\Notice
{
	/**
	 * Список статусов
	 *
	 * @var array
	 */
	static $statusList = [
		self::STATUS_NEW => 'Новый',
		self::STATUS_VIEW => 'Просмотрен',
		self::STATUS_DONE => 'Закрыт',
	];

	/**
	 * Список типов
	 *
	 * @var array
	 */
	static $typeList = [
		self::TYPE_NONE => 'Без типа',
		self::TYPE_CALLBACK => 'Обратный звонок',
	];

	/**
	 * Название статуса
	 *
	 * @param int $status
	 *
	 * @return string|null
	 */
	public static function statusName($status)
	{
		return self::$statusList[$status] ?? null;
	}

	/**
	 * Название типа
	 *
	 * @param int $type
	 *
	 * @return string|null
	 */
	public static function typeName($type)
	{
		return self::$typeList[$type] ?? null;
	}

	/**
	 * Возвращает название текущего статуса
	 *
	 * @return string|null
	 */
	public function getStatusName()
	{
		return self::statusName($this->status);
	}

	/**
	 * Возвращает название текущего типа
	 *
	 * @return string|null
	 */
	public function getTypeName()
	{
		return self::typeName($this->type);
	}

	/**
	 * Уведомление просмотрено
	 *
	 * @return int
	 */
	public function view()
	{
		return ($this->status == self::STATUS_NEW) && $this->updateAttributes([
			'status' => self::STATUS_VIEW,
			'user_id' => (int)Yii::$app->user->id,
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}

	/**
	 * Уведомление закрыто
	 *
	 * @return int
	 */
	public function done()
	{
		return ($this->status == self::STATUS_VIEW) && $this->updateAttributes([
			'status' => self::STATUS_DONE,
			'user_id' => (int)Yii::$app->user->id,
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}
}
