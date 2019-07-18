<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_order".
 *
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property int $legal
 * @property string $name
 * @property string $passport_series
 * @property string $passport_number
 * @property string $inn
 * @property string $kpp
 * @property string $postcode
 * @property string $address
 * @property string $fax
 * @property string $representive_name
 * @property string $representive_position
 * @property string $bank_name
 * @property string $bik
 * @property string $account_number
 * @property string $correspondent_account
 */
class UserOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['legal'], 'integer'],
            [
                [
                    'email',
                    'name',
                    'address',
                    'fax',
                    'representive_name',
                    'representive_position',
                    'bank_name',
                    'bik',
                    'account_number',
                    'correspondent_account'
                ],
                'string',
                'max' => 255
            ],
            [['phone'], 'string', 'max' => 10],
            [['passport_series'], 'string', 'max' => 4],
            [['passport_number', 'postcode'], 'string', 'max' => 6],
            [['inn'], 'string', 'max' => 12],
            [['kpp'], 'string', 'max' => 9],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'phone' => 'Phone',
            'legal' => 'Legal',
            'name' => 'Name',
            'passport_series' => 'Passport Series',
            'passport_number' => 'Passport Number',
            'inn' => 'Inn',
            'kpp' => 'Kpp',
            'postcode' => 'Postcode',
            'address' => 'Address',
            'fax' => 'Fax',
            'representive_name' => 'Representive Name',
            'representive_position' => 'Representive Position',
            'bank_name' => 'Bank Name',
            'bik' => 'Bik',
            'account_number' => 'Account Number',
            'correspondent_account' => 'Correspondent Account',
        ];
    }

    public function copyFromUser(User $user)
    {
        foreach ($this->attributeLabels() as $attribute => $label) {
            if ($user->hasAttribute($attribute)) {
                $this->$attribute = $user->$attribute;
            }
        }
    }

    /**
     * @return bool
     */
    public function isLegal(): bool
    {
        return in_array($this->legal, [User::LEGAL_YES, User::LEGAL_IP]);
    }
}
