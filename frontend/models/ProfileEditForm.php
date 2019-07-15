<?php

namespace frontend\models;

use common\models\User;
use yii\base\InvalidConfigException;
use yii\base\Model;
use common\helpers\Validator;

class ProfileEditForm extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var int
     */
    public $postcode;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $fax;


    /**
     * @var int Individual
     */
    public $passportSeries;

    /**
     * @var int Individual
     */
    public $passportNumber;


    /**
     * @var int Legal
     */
    public $inn;

    /**
     * @var int Legal
     */
    public $kpp;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var int
     */
    protected $legal;

    //необязательный для юр.лиц/ИП
    public $bank_name;
    public $bik;
    public $account_number;
    public $correspondent_account;
    public $representive_name;
    public $representive_position;

    /**
     * @var boolean
     */
    public $isLegal;

    /**
     * ProfileEditForm constructor.
     * @param int $userId
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(int $userId, array $config = [])
    {
        $this->setUser($userId);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $user = $this->user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->postcode = $user->postcode;
        $this->address = $user->address;
        $this->fax = $user->fax;
        $this->passportSeries = $user->passport_series;
        $this->passportNumber = $user->passport_number;
        $this->inn = $user->inn;
        $this->kpp = $user->kpp;
        $this->legal = $user->legal;
        $this->representive_name = $user->representive_name;
        $this->representive_position = $user->representive_position;
        $this->bank_name = $user->bank_name;
        $this->bik = $user->bik;
        $this->account_number = $user->account_number;
        $this->correspondent_account = $user->correspondent_account;
        $this->isLegal = $user->isLegal();
    }

    /**
     * @param int $id
     * @throws InvalidConfigException
     */
    protected function setUser($id)
    {
        $this->user = User::findOne($id);
        if ($this->user === null) {
            throw new InvalidConfigException('User not found.');
        }
    }

    /**
     * @return int
     */
    public function getLegal()
    {
        return (int)$this->legal;
    }

    /**
     * @return int
     */
    public function getIsLegalIp()
    {
        return (int)$this->legal == User::LEGAL_IP;
    }

    /**
     * @return int
     */
    public function getIsLegal()
    {
        return (int)$this->legal == User::LEGAL_YES;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'address', 'postcode'], 'required'],
            [['name', 'email', 'address', 'fax'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 10],
            [['postcode'], 'string', 'length' => 6],
            [['passportSeries'], 'string', 'length' => 4],
            [['passportNumber'], 'string', 'length' => 6],
            [['inn'], 'checkInn'],
            [['kpp'], 'checkKpp'],
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
                ['representive_name', 'representive_position', 'account_number', 'bank_name', 'bik', 'correspondent_account'],
                'string',
                'max' => 255
            ],
            ['email', 'email'],
            [
                'phone',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'Пользователь с таким телефоном уже существует.',
                'when' => function ($model) {
                    return $model->phone != $this->user->phone;
                }
            ],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'Пользователь с таким E-mail уже существует.',
                'when' => function ($model) {
                    return $model->email != $this->user->email;
                }
            ],
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
    public function attributeLabels()
    {
        return [
            'name' => 'ФИО *',
            'companyName' => 'Название компании *',
            'email' => 'E-mail *',
            'phone' => 'Телефон *',
            'postcode' => 'Почтовый индекс *',
            'address' => 'Полный адрес *',
            'fax' => 'Факс',
            'passportSeries' => 'Серия паспорта',
            'passportNumber' => 'Номер паспорта',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'legal' => 'Физ/Юр лицо',
            'representive_name' => 'ФИО уполномоченного представителя',
            'correspondent_account' => 'Корреспондентский счет ЮЛ',
            'representive_position' => 'Должность уполномоченного представителя',
            'bank_name' => 'Наименование банка ЮЛ',
            'bik' => 'БИК Банка ЮЛ',
            'account_number' => 'Расчетный счет ЮЛ',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            $this->formattingPhone('phone');
            $this->formattingInt(['postcode', 'passportSeries', 'passportNumber', 'inn', 'kpp']);

            return true;
        }

        return false;
    }

    /**
     * Formatting phone attribute
     * @param string $attribute
     */
    protected function formattingPhone(string $attribute)
    {
        if ($this->hasProperty($attribute)) {
            $this->$attribute = mb_substr(preg_replace('/[^0-9]/', '', $this->$attribute), -10);
        }
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

    /**
     * Save User model
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $user = $this->user;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->postcode = $this->postcode;
            $user->address = $this->address;
            $user->fax = $this->fax;
            $user->passport_series = $this->passportSeries;
            $user->passport_number = $this->passportNumber;
            $user->inn = $this->inn;
            $user->kpp = $this->kpp;
            $user->legal = $this->legal;
            $user->representive_name = $this->representive_name;
            $user->representive_position = $this->representive_position;
            $user->account_number = $this->account_number;
            $user->bank_name = $this->bank_name;
            $user->bik = $this->bik;

            return $user->save();

        }

        return false;
    }
}