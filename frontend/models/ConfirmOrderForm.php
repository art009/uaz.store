<?php

namespace frontend\models;

use common\models\Order;
use common\models\User;
use yii\base\Model;

/**
 * Class ConfirmOrderForm
 *
 * @package frontend\models
 */
class ConfirmOrderForm extends Model
{
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
    public $email;

    /**
     * @var int
     */
    public $delivery;

    /**
     * @var int
     */
    public $payment;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var User
     */
    protected $user;

    public $bank_name;
    public $bik;
    public $account_number;
    public $correspondent_account;
    public $representive_name;
    public $representive_position;

    public $isLegal;
    /**
     * @var int
     */
    public $inn;
    public $kpp;

    /**
     * @return Order
     */
    protected function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    protected function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return User
     */
    protected function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    protected function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $user = $this->getUser();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->representive_name = $user->representive_name;
        $this->representive_position = $user->representive_position;
        $this->bank_name = $user->bank_name;
        $this->bik = $user->bik;
        $this->account_number = $user->account_number;
        $this->correspondent_account = $user->correspondent_account;
        $this->inn = $user->inn;
        $this->isLegal = $user->isLegal();
    }

    public function getIsLegalIp()
    {
        return $this->user->legal == User::LEGAL_IP;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'name', 'email', 'delivery', 'payment'], 'required'],
            [
                [
                    'phone',
                    'name',
                    'email',
                    'representive_name',
                    'representive_position',
                    'account_number',
                    'bank_name',
                    'bik',
                    'correspondent_account'
                ],
                'trim'
            ],
            [
                [
                    'representive_name',
                    'representive_position',
                    'account_number',
                    'bank_name',
                    'bik',
                    'correspondent_account'
                ],
                'string',
                'max' => 255
            ],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['name', 'safe'],
            [['inn'], 'checkInn'],
            [['kpp'], 'checkKpp'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'name' => 'Контактное лицо',
            'email' => 'E-mail',
            'delivery' => 'Способ доставки',
            'payment' => 'Вариант оплаты',
            'representive_name' => 'ФИО уполномоченного представителя',
            'correspondent_account' => 'Корреспондентский счет ЮЛ',
            'representive_position' => 'Должность уполномоченного представителя',
            'bank_name' => 'Наименование банка ЮЛ',
            'bik' => 'БИК Банка ЮЛ',
            'account_number' => 'Расчетный счет ЮЛ',
            'inn' => 'ИНН',
        ];
    }

    public function checkInn()
    {
        return \Validator::checkInn($this);
    }

    public function checkKpp()
    {
        return \Validator::checkKpp($this);
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        if (mb_strlen(preg_replace('/[^0-9]/', '', $this->phone)) < 11) {
            $this->addError('phone', 'Необходим 10-значный номер телефона.');
        }

        $phone = $this->getPhoneNumber();
        $user = User::findOne(['phone' => $phone]);
        if ($user && $user->getId() != $this->getUser()->getId()) {
            $this->addError('phone', 'Введенный номер телефона занят.');
        }

        $user = User::findOne(['email' => $this->email]);
        if ($user && $user->getId() != $this->getUser()->getId()) {
            $this->addError('email', 'Введенный E-mail занят.');
        }

        return parent::afterValidate();
    }

    /**
     * @return string
     */
    protected function getPhoneNumber(): string
    {
        return mb_substr(preg_replace('/[^0-9]/', '', $this->phone), -10);
    }

    /**
     * @return bool
     */
    protected function checkUser(): bool
    {
        $user = $this->getUser();

        return $this->name == $user->name && $this->getPhoneNumber() == $user->phone && $this->email == $user->email;
    }

    /**
     * @return bool
     */
    protected function updateUser(): bool
    {
        $user = $this->getUser();

        return (bool)$user->updateAttributes([
            'name' => $this->name,
            'phone' => $this->getPhoneNumber(),
            'email' => $this->email,
            'representive_name' => $this->representive_name,
            'representive_position' => $this->representive_position,
            'account_number' => $this->account_number,
            'bank_name' => $this->bank_name,
            'bik' => $this->bik,
            'inn' => $this->inn,
            'kpp' => $this->kpp
        ]);
    }

    /**
     * @return bool
     */
    protected function checkOrder(): bool
    {
        $order = $this->getOrder();

        return $this->delivery == $order->delivery_type && $this->payment == $order->payment_type;
    }

    /**
     * @return bool
     */
    protected function updateOrder(): bool
    {
        $order = $this->getOrder();

        return (bool)$order->updateAttributes([
            'delivery_type' => $this->delivery,
            'payment_type' => $this->payment,
        ]);
    }

    /**
     * @return bool
     */
    public function confirm(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        return ($this->checkUser() || $this->updateUser()) && ($this->checkOrder() || $this->updateOrder());
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            $this->formattingInt(['inn', 'kpp']);

            return true;
        }

        return false;
    }

    /**
     * Formatting integer attributes
     * @param array $attributes
     */
    protected function formattingInt(array $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($this->hasProperty($attribute)) {
                $this->$attribute = str_replace([' ', '_'], '', $this->$attribute);
            }
        }
    }
}
