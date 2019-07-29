<?php

namespace frontend\models;

use common\helpers\Validator;
use common\models\Order;
use common\models\User;
use common\models\UserOrder;
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
    public $fax;
    public $postcode;
    public $address;
    public $passportSeries;
    public $passportNumber;

    public $isLegal;
    public $legal;
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
        $this->kpp = $user->kpp;
        $this->fax = $user->fax;
        $this->postcode = $user->postcode;
        $this->address = $user->address;
        $this->isLegal = $user->isLegal();
        $this->legal = $user->legal;
        $this->passportSeries = $user->passport_series;
        $this->passportNumber = $user->passport_number;
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
            [['phone', 'name', 'email', 'delivery', 'payment', 'legal'], 'required'],
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
                    'correspondent_account',
                    'address',
                    'fax',
                    'postcode'
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
                    'correspondent_account',
                    'address',
                    'fax',
                    'postcode'
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
            [['passportSeries'], 'string', 'length' => 4],
            [['passportNumber'], 'string', 'length' => 6],
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
            'kpp' => 'КПП',
            'passportSeries' => 'Серия паспорта',
            'passportNumber' => 'Номер паспорта',
        ];
    }

    public function checkInn()
    {
        return Validator::checkInn($this);
    }

    public function checkKpp()
    {
        return Validator::checkKpp($this);
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
    protected function createUserOrder(): bool
    {
        $userOrder = new UserOrder();
        $attributes = [
            'name' => $this->name,
            'phone' => $this->getPhoneNumber(),
            'email' => $this->email,
            'inn' => $this->inn,
            'kpp' => $this->kpp,
            'bank_name' => $this->bank_name,
            'bik' => $this->bik,
            'account_number' => $this->account_number,
            'correspondent_account' => $this->correspondent_account,
            'representive_name' => $this->representive_name,
            'representive_position' => $this->representive_position,
            'fax' => $this->fax,
            'postcode' => $this->postcode,
            'address' => $this->address,
            'legal' => $this->legal,
            'passport_series' => $this->passportSeries,
            'passport_number' => $this->passportNumber,
        ];
        $userOrder->setAttributes($attributes);
        $userOrder->save();
        return $this->order->updateAttributes(['user_id' => $userOrder->id]);
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

        return ($this->checkOrder() || $this->updateOrder()) && ($this->createUserOrder());
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            $this->formattingInt(['inn', 'kpp', 'passportSeries', 'passportNumber', 'postcode']);

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

    public function getAttributeLabel($attribute)
    {
        if ($this->user->isLegal()) {
            if ($attribute == 'name') {
                return 'Название организации';
            }
        }
        if ($attribute == 'address') {
            return 'Адрес';
        }
        if ($attribute == 'postcode') {
            return 'Индекс';
        }

        return parent::getAttributeLabel($attribute);
    }
}
