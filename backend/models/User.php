<?php

namespace backend\models;

use codeonyii\yii2validators\AtLeastValidator;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use common\components\AppHelper;
use common\components\ImageHandler;

/**
 * Class User
 *
 * @property UploadedFile $imageFile
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
	 * @var UploadedFile
	 */
	public $imageFile;

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

			[['passport_series', 'passport_number', 'inn', 'kpp', 'postcode'], 'default', 'value' => 0],

			[['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],

			[['phone', 'email', 'password', 'role', 'legal', 'name', 'photo', 'passport_series', 'passport_number', 'inn',
				'kpp', 'postcode', 'address', 'offer_accepted', 'fax'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'imageFile' => 'Загружаемая фотография',
		]);
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

			if ($this->imageFile) {
				$name = md5(microtime() . $this->email) . '.' . $this->imageFile->extension;
				$uploadsFolder = AppHelper::uploadsFolder();
				if ($this->imageFile->saveAs($uploadsFolder . '/' . self::FOLDER . '/' . $name)) {
					$this->deleteImages();
					$this->photo = $name;

					/* @var $imageHandler ImageHandler */
					$imageHandler = Yii::$app->ih;
					$imageHandler
						->load($uploadsFolder . '/' . self::FOLDER . '/' . $name)
						->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_LEFT_BOTTOM)
						->save($uploadsFolder . '/' . self::FOLDER . '/' . $name, false, 100)
						->reload()
						->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
						->resizeCanvas(self::SMALL_IMAGE_WIDTH, self::SMALL_IMAGE_HEIGHT)
						->save($uploadsFolder . '/' . self::FOLDER_SMALL . '/' . $name, false, 100);
				} else {
					$this->addError('imageFile', 'Директория недоступна для записи: ' . $uploadsFolder . '/' . self::FOLDER . '/');
					return false;
				}
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
	 * @inheritdoc
	 */
	public function load($data, $formName = null)
	{
		$result = parent::load($data, $formName = null);
		$this->imageFile = UploadedFile::getInstance($this, 'imageFile');

		return $result;
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

	/**
	 * Deletes model image
	 */
	protected function deleteImages()
	{
		if ($this->photo) {
			$uploadsFolder = AppHelper::uploadsFolder();
			@unlink($uploadsFolder . '/' . self::FOLDER . '/' . $this->photo);
			@unlink($uploadsFolder . '/' . self::FOLDER_SMALL . '/' . $this->photo);
		}
	}
}
