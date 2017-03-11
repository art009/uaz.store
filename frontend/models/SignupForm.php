<?php
namespace frontend\models;

use codeonyii\yii2validators\AtLeastValidator;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $password;
    public $legal;
    public $offer_accepted;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

	        [['phone', 'email'], AtLeastValidator::className(), 'in' => ['phone', 'email'],
		        'message' => 'Необходимо заполнить E-mail или Телефон'],

	        ['phone', 'trim'],
	        ['phone', 'unique', 'targetClass' => '\common\models\User',
		        'message' => 'Пользователь с таким телефоном уже существует.'],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User',
	            'message' => 'Пользователь с таким E-mail уже существует.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

	        ['legal', 'required'],

	        ['offer_accepted', 'required', 'requiredValue' => 1, 'message' => 'Необходимо согласие с условиями'],
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => 'E-mail',
			'phone' => 'Телефон',
			'legal' => 'Физ/Юр лицо',
			'name' => 'ФИО/Название компании',
			'offer_accepted' => 'Согласие с условиями',
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->legal = $this->legal;
        $user->phone = $this->phone;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->offer_accepted = $this->offer_accepted;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
