<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $email
 * @property string $phone
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $role
 * @property integer $legal
 * @property string $name
 * @property integer $passport_series
 * @property integer $passport_number
 * @property integer $inn
 * @property integer $kpp
 * @property integer $postcode
 * @property string $address
 * @property string $fax
 * @property string $photo
 * @property integer $offer_accepted
 * @property integer $accepted_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Order[] $orders
 */
class User extends ActiveRecord implements IdentityInterface
{
	const FOLDER = 'user';
	const FOLDER_SMALL = self::FOLDER . '/s';

	const SMALL_IMAGE_WIDTH = 100;
	const SMALL_IMAGE_HEIGHT = 100;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    const ROLE_CLIENT = 0;
    const ROLE_ADMIN = 1;
    const ROLE_MANAGER = 2;

    const LEGAL_NO = 0;
    const LEGAL_YES = 1;

	/**
	 * Список статусов
	 *
	 * @var array
	 */
    static $statusList = [
    	self::STATUS_DELETED => 'Удален',
    	self::STATUS_ACTIVE => 'Активен',
	];

	/**
	 * Список ролей
	 *
	 * @var array
	 */
    static $roleList = [
    	self::ROLE_CLIENT => 'Клиент',
    	self::ROLE_ADMIN => 'Администратор',
    	self::ROLE_MANAGER => 'Менеджер',
	];

	/**
	 * Физ / Юр лицо
	 *
	 * @var array
	 */
    static $legalList = [
    	self::LEGAL_NO => 'Физ лицо',
    	self::LEGAL_YES => 'Юр лицо',
	];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_BLOCKED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by phone
     *
     * @param string $phone
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders()
	{
		return $this->hasMany(Order::className(), ['user_id' => 'id']);
	}
}
