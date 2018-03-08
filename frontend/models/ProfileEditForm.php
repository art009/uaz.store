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
    public $password_series;

    /**
     * @var int Individual
     */
    public $password_number;


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
        $this->configure();

        parent::__construct($config);
    }

    /**
     * Set attributes
     * @return void
     */
    protected function configure()
    {
        if ($this->user instanceof User) {
            foreach ($this->user->getAttributes() as $attribute => $value) {
                if ($this->hasProperty($attribute)) {
                    $this->$attribute = $value;
                }
            }
        }
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
    public function getLegal(): int
    {
        return $this->legal;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            [['name', 'email', 'address', 'fax'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 10],
            [['postcode', 'password_series', 'password_number', 'inn', 'kpp'], 'integer'],
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
            'password_series' => 'Серия паспорта',
            'password_number' => 'Номер паспорта',
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
            $this->formattingInt(['postcode', 'password_series', 'password_number', 'inn', 'kpp']);

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
                $this->$attribute = str_replace(" ", "", $this->$attribute);
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
            foreach ($this->getAttributes() as $attribute => $value) {
                if ($this->user->hasProperty($attribute)) {
                    $this->user->$attribute = $value;
                }
            }

            if ($this->user->validate()) {
                return $this->user->save();
            } else {
                $this->errors = $this->user->getErrors();
            }
        }

        return false;
    }
}