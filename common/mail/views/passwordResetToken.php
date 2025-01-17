<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/set-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Здравствуйте,</p>

    <p>Перейдите по ссылке снизу для установки вашего нового пароля:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
