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
    'frontendUrl' => 'http://uaz.store/',
	'payment.secretSeed' => 'zyr5v1wTe',
    'maxCallbackAttempts' => 3,
    'maxQuestionAttempts' => 3,
    'delayBetweenCallbackAttempts' => 300,
    'delayBetweenQuestionAttempts' => 300
];
