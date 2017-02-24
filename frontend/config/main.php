<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language' => 'ru-RU',
	'sourceLanguage' => 'ru-RU',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
			'cookieValidationKey' => 'IpFuTmh9WWkmdRAO',
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
		'redis' => [
			'class' => 'yii\redis\Connection',
			'hostname' => 'localhost',
			'port' => 6379,
		],
		'session' => [
			'name' => 'advanced-frontend',
		],
		'cache' => [
			'class' => 'yii\redis\Cache',
		],
		'view' => [
			'class' => '\rmrevin\yii\minify\View',
			'enableMinify' => !YII_DEBUG,
			'webPath' => '@web', // path alias to web base
			'basePath' => '@frontend/web', // path alias to web base
			'minifyPath' => '@frontend/web/min', // path alias to save minify result
			'jsPosition' => [ \yii\web\View::POS_END ], // positions of js files to be minified
			'concatCss' => true, // concatenate css
			'minifyCss' => true, // minificate css
			'concatJs' => true, // concatenate js
			'minifyJs' => true, // minificate js
			'minifyOutput' => true, // minificate result html page
			'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
			'expandImports' => true, // whether to change @import on content
			'compressOptions' => ['extra' => true], // options for compress
			'jsOptions' => ['async' => true],
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
		'cart' => [
			'class' => 'frontend\components\Cart',
		],
    ],
    'params' => $params,
];
