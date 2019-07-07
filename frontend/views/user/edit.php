<?php

use common\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\ProfileEditForm */

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-profile">
    <h1><?php echo Html::encode($this->title) ?></h1>
    <a href="/logout" data-method="post" class="site-btn logout-btn">Выход</a>
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <div class="form-header">
        <div class="col-xs-6 text-center">Профиль</div>
        <div class="col-xs-6 text-center"><a href="/order">Заказы</a></div>
    </div>
    <div class="clearfix"></div>

    <?php echo $form
        ->field($model, 'legal')
        ->inline()
        ->radioList(User::$legalList, [
            'item' => function ($index, $label, $name, $checked, $value) {

                $html = Html::beginTag('span', ['class' => 'radio-inline']);
                $html .= Html::radio($name, $checked, [
                    'value' => $value,
                    'id' => 'radio_' . $index,
                    'checked' => $checked,
                    'disabled' => (!$checked ? 'disabled' : '')
                ]);
                $html .= Html::label($label, 'radio_' . $index);
                $html .= Html::endTag('span');

                return $html;
            }
        ])
        ->label(false);
    ?>

    <?php echo $form->field($model, 'name', [
        'template' => '{input}{error}{hint}'
    ])->textInput(['placeholder' => $model->getAttributeLabel('name')]); ?>

    <?php echo $form->field($model, 'email', [
        'template' => '{input}{error}{hint}'
    ])->textInput(['placeholder' => $model->getAttributeLabel('email')]); ?>

    <?php echo $form->field($model, 'phone', [
        'template' => '{input}{error}{hint}'
    ])->widget(MaskedInput::className(), [
        'mask' => '+7(999)999-99-99',
        'options' => [
            'class' => 'form-control tel_input',
            'placeholder' => $model->getAttributeLabel('phone'),
        ],
        'clientOptions' => [
            'clearIncomplete' => false
        ],
    ]); ?>

    <?php echo $form->field($model, 'fax', [
        'template' => '{input}{error}{hint}'
    ])->widget(MaskedInput::className(), [
        'mask' => '+7(999)999-99-99',
        'options' => [
            'class' => 'form-control tel_input',
            'placeholder' => $model->getAttributeLabel('fax'),
        ],
        'clientOptions' => [
            'clearIncomplete' => false
        ],
    ]); ?>

    <?php if (!$model->isLegal): ?>
        <?php echo $form->field($model, 'passportSeries', [
            'template' => '{input}{error}{hint}'
        ])->widget(MaskedInput::className(), [
            'mask' => '99 99',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('passportSeries'),
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ],
        ]); ?>

        <?php echo $form->field($model, 'passportNumber', [
            'template' => '{input}{error}{hint}'
        ])->widget(MaskedInput::className(), [
            'mask' => '999999',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->getAttributeLabel('passportNumber'),
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ],
        ]); ?>
    <?php endif; ?>

    <?php echo $form->field($model, 'postcode', [
        'template' => '{input}{error}{hint}'
    ])->widget(MaskedInput::className(), [
        'mask' => '999 999',
        'options' => [
            'class' => 'form-control tel_input',
            'placeholder' => $model->getAttributeLabel('postcode'),
        ],
        'clientOptions' => [
            'clearIncomplete' => false
        ],
    ]); ?>

    <?php echo $form->field($model, 'address', [
        'template' => '{input}{error}{hint}'
    ])->textInput(['placeholder' => $model->getAttributeLabel('address')]); ?>

    <?php if ($model->isLegal): ?>
        <?php if ($model->getIsLegalIp()) {
            echo $form->field($model, 'inn', [
                'template' => '{input}{error}{hint}'
            ])->widget(MaskedInput::className(), [
                'options' => [
                    'class' => 'form-control tel_input',
                    'placeholder' => $model->getAttributeLabel('inn') . ' (12 цифр)',
                ],
                'clientOptions' => [
                    'alias' => '999 999 999 999',
                ],
            ]);
        } else {
            echo $form->field($model, 'inn', [
                'template' => '{input}{error}{hint}'
            ])->widget(MaskedInput::className(), [
                'options' => [
                    'class' => 'form-control tel_input',
                    'placeholder' => $model->getAttributeLabel('inn') . ' (10 цифр)',
                ],
                'clientOptions' => [
                    'alias' => '999 999 9999',
                ],
            ]);
        } ?>

        <?php echo $form->field($model, 'kpp', [
            'template' => '{input}{error}{hint}'
        ])->widget(MaskedInput::className(), [
            'mask' => '999 999 999',
            'options' => [
                'class' => 'form-control tel_input',
                'placeholder' => $model->getAttributeLabel('kpp'),
            ],
            'clientOptions' => [
                'clearIncomplete' => false
            ],
        ]); ?>

        <?= $form->field($model, 'bank_name', [
            'template' => '{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control business'
            ]
        ])->textInput([
            'placeholder' => $model->getAttributeLabel('bank_name'),
        ]) ?>

        <?= $form->field($model, 'bik', [
            'template' => '{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control business'
            ]
        ])->textInput([
            'placeholder' => $model->getAttributeLabel('bik'),
        ]) ?>

        <?= $form->field($model, 'account_number', [
            'template' => '{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control business'
            ]
        ])->textInput([
            'placeholder' => $model->getAttributeLabel('account_number'),
        ]) ?>

        <?= $form->field($model, 'correspondent_account', [
            'template' => '{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control business'
            ]
        ])->textInput([
            'placeholder' => $model->getAttributeLabel('correspondent_account'),
        ]) ?>

        <?= $form->field($model, 'representive_name', [
            'template' => '{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control business'
            ]
        ])->textInput([
            'placeholder' => $model->getAttributeLabel('representive_name'),
        ]) ?>

        <?= $form->field($model, 'representive_position', [
            'template' => '{input}{error}{hint}',
            'inputOptions' => [
                'class' => 'form-control business'
            ]
        ])->textInput([
            'placeholder' => $model->getAttributeLabel('representive_position'),
        ]) ?>
    <?php endif; ?>

    <div class="form-group pull-left">
        <?php echo Html::a('Сменить пароль', ['change-password']) ?>
    </div>

    <br>
    <div class="form-group pull-left">
        <p class="help-block" style="color: whitesmoke">Поля отмеченные * обязательны</p>
    </div>

    <?php echo Html::submitButton('Сохранить', ['class' => 'site-btn', 'name' => 'login-button']) ?>

    <?php ActiveForm::end(); ?>

</div>
