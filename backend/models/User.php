<?php

namespace backend\models;

use codeonyii\yii2validators\AtLeastValidator;

/**
 * Class User
 *
 * @package backend\models
 */
class User extends \common\models\User
{
	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @return array
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_CREATE] = ['phone', 'email', 'password', 'role', 'legal', 'name', 'photo',
			'passport_series', 'passport_number', 'inn',
			'kpp', 'postcode', 'address', 'offer_accepted', 'fax'];
		$scenarios[self::SCENARIO_UPDATE] = ['phone', 'email', 'password', 'role', 'legal', 'name', 'photo',
			'passport_series', 'passport_number', 'inn',
			'kpp', 'postcode', 'address', 'offer_accepted', 'fax'];

		return $scenarios;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['phone', 'email'], AtLeastValidator::className(), 'in' => ['phone', 'email'],
				'message' => 'Необходимо заполнить E-mail или Телефон'],

			['email', 'trim'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'message' => 'Пользователь с таким E-mail уже существует.'],

			['phone', 'trim'],
			['phone', 'unique', 'message' => 'Пользователь с таким телефоном уже существует.'],

			['password', 'required', 'on' => self::SCENARIO_CREATE],
			['password', 'string', 'min' => 5],

			[['phone', 'email', 'password', 'role', 'legal', 'name', 'photo', 'passport_series', 'passport_number', 'inn',
				'kpp', 'postcode', 'address', 'offer_accepted', 'fax'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'email' => 'E-mail',
			'phone' => 'Телефон',
			'auth_key' => 'Auth Key',
			'password_hash' => 'Хеш пароля',
			'password_reset_token' => 'Токен сброса пароля',
			'status' => 'Статус',
			'role' => 'Роль',
			'legal' => 'Физ/Юр лицо',
			'name' => 'ФИО/Название компании',
			'passport_series' => 'Серия паспорта',
			'passport_number' => 'Номер паспорта',
			'inn' => 'ИНН',
			'kpp' => 'КПП',
			'postcode' => 'Почтовый индекс',
			'address' => 'Полный адрес',
			'fax' => 'Факс',
			'photo' => 'Фотография',
			'offer_accepted' => 'Согласие с офертой',
			'accepted_at' => 'Время согласия',
			'created_at' => 'Время создания',
			'updated_at' => 'Время обновления',
			'password' => 'Пароль',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeValidate()
	{
		if (parent::beforeValidate()) {

			$this->phone = mb_substr(preg_replace('/[^0-9]/', '', $this->phone), -10);

			return true;
		}

		return false;
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			if ($this->isNewRecord) {
				$this->setPassword($this->password);
				$this->generateAuthKey();
			}
			$this->email = $this->email ?: null;
			$this->phone = $this->phone ?: null;
			if ($this->offer_accepted && !$this->getOldAttribute('offer_accepted')) {
				$this->accepted_at = date('Y-m-d H:i:s');
			}

			return true;
		}

		return false;
	}

	/**
	 * @param bool $insert
	 *
	 * @param array $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
	}


	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			// Физически не удаляем
			return false;
		} else {
			return false;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		$this->scenario = self::SCENARIO_UPDATE;

		parent::afterFind();
	}

	/**
	 * Название статуса
	 *
	 * @return null
	 */
	public function getStatusName()
	{
		return array_key_exists($this->status, self::$statusList) ? self::$statusList[$this->status] : null;
	}

	/**
	 * Название роли
	 *
	 * @return null
	 */
	public function getRoleName()
	{
		return array_key_exists($this->role, self::$roleList) ? self::$roleList[$this->role] : null;
	}

	/**
	 * Физ / Юр лицо
	 *
	 * @return null
	 */
	public function getLegalName()
	{
		return array_key_exists($this->legal, self::$legalList) ? self::$legalList[$this->legal] : null;
	}

	/**
	 * Список ролей для формы
	 *
	 * @return array
	 */
	public static function getFormRoleList()
	{
		return [
			self::ROLE_CLIENT => 'Клиент',
			self::ROLE_MANAGER => 'Менеджер',
		];
	}
}
