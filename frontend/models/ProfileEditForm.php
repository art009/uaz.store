<?php

namespace frontend\models;


use common\models\User;
use yii\base\InvalidConfigException;
use yii\base\Model;

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
            [['inn'], 'string', 'min' => 10, 'max' => 12],
            [['kpp'], 'string', 'length' => 9],
            [['phone', 'name', 'email'], 'trim'],
            ['email', 'email'],
            ['phone', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'Пользователь с таким телефоном уже существует.',
                'when' => function($model) {
                    return $model->phone != $this->user->phone;
                }],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'Пользователь с таким E-mail уже существует.',
                'when' => function($model) {
                    return $model->email != $this->user->email;
                }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'ФИО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'postcode' => 'Почтовый индекс',
            'address' => 'Полный адрес',
            'fax' => 'Факс',
            'passportSeries' => 'Серия паспорта',
            'passportNumber' => 'Номер паспорта',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'legal' => 'Физ/Юр лицо',
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

            return $user->save();

        }

        return false;
    }
}