<?php

namespace frontend\models;


use yii\base\InvalidParamException;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @var \common\models\User
     */
    private $user;

    /**
     * ChangePasswordForm constructor.
     * @param int $userId
     * @param array $config
     */
    public function __construct(int $userId, array $config = [])
    {
        $this->user = \common\models\User::findIdentity($userId);
        if ($this->user === null) {
            throw new InvalidParamException("User not found.");
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            [['password', 'password_confirm'], 'string', 'min' => 6],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_confirm' => 'Повторите пароль',
        ];
    }

    /**
     * @return bool
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $this->user->setPassword($this->password);

            return $this->user->save(false);
        }

        return false;
    }
}