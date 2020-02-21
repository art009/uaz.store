<?php
setlocale(LC_ALL, 'ru_RU');
ini_set('date.timezone', 'Europe/Moscow');

return [
    'adminEmail' => 'admin@uaz.store',
    'supportEmail' => 'support@uaz.store',
    'fromEmail' => 'no-reply@uaz.store',
    'fromName' => 'UAZ.STORE',
    'user.passwordResetTokenExpire' => 3600,
    'backendUrl' => 'http://manage.uaz.store/',
    'frontendUrl' => 'https://uaz.store/',
    'payment.secretSeed' => 'zyr5v1wTe',
    'maxCallbackAttempts' => 3,
    'maxQuestionAttempts' => 3,
    'maxOrderAttempts' => 3,
    'delayBetweenCallbackAttempts' => 300,
    'delayBetweenQuestionAttempts' => 300,
    'delayBetweenOrderAttempts' => 300,
    'whatsappPhone' => '79530226932',
    'viberPhone' => '79530226932',
];
