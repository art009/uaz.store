<?php

namespace common\helpers;

use common\models\User;
use yii\base\Model;

class Validator
{
    public static function checkInn(Model $model)
    {
        $innLength = strlen($model->inn);
        if ($model->legal == User::LEGAL_IP) {
            if ($innLength != 12) {
                $model->addError('inn', 'Значение «ИНН» должно содержать 12 символов.');
            }
        }
        if ($model->legal == User::LEGAL_YES) {
            if ($innLength != 10) {
                $model->addError('inn', 'Значение «ИНН» должно содержать 10 символов.');
            }
        }

        return true;
    }

    public static function checkKpp(Model $model)
    {
        if ($model->legal > 0) {
            $kppLength = strlen($model->kpp);
            if ($kppLength != 9) {
                $model->addError('kpp', 'Значение «КПП» должно содержать 9 символов.');
            }
        }

        return true;
    }
}