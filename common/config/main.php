<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
	    'redis' => [
		    'class' => 'yii\redis\Connection',
		    'hostname' => 'localhost',
		    'port' => 6379,
	    ],
	    'cache' => [
		    'class' => 'yii\redis\Cache',
	    ],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'viewPath' => '@common/mail',
			'useFileTransport' => false,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.yandex.ru',
				'username' => 'no-reply@uaz.store',
				'password' => 'gybpbpwpcpkgvokh',
				'port' => '465',
				'encryption' => 'ssl',
			],
		],
	    'sphinx' => [
		    'class' => 'yii\sphinx\Connection',
		    'dsn' => 'mysql:host=127.0.0.1;port=9306',
		    'username' => '',
		    'password' => '',
	    ],
    ],
];
