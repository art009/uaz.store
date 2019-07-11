<?php

use backend\models\User;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'modules' => [
	    'pms' => [
		    'class' => 'app\modules\pms\Module',
	    ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
		'redis' => [
			'class' => 'yii\redis\Connection',
			'hostname' => 'localhost',
			'port' => 6379,
		],
		'session' => [
			'name' => 'advanced-backend',
		],
		'cache' => [
			'class' => 'yii\redis\Cache',
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'notamedia\sentry\SentryTarget',
                    'dsn' => 'https://88a792f1db2f49e2815dbd2e0b79ff84@sentry.io/1503002',
                    'levels' => ['error', 'warning'],
                ],
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
                '<action:(login|logout|error)>' => 'site/<action>',
                'catalog' => 'catalog-category',
            ],
        ],
        'ih' => [
            'class' => 'common\components\ImageHandler',
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
    ],
    'params' => $params,
	'as beforeRequest' => [
		'class' => 'yii\filters\AccessControl',
		'rules' => [
			[
				'allow' => true,
				'matchCallback' => function () {
					if (Yii::$app->controller->id == 'site' && (in_array(Yii::$app->controller->action->id, ['login', 'error']))) {
						return true;
					} elseif (Yii::$app->user->isGuest == false && Yii::$app->user->identity->role == User::ROLE_ADMIN) {
						return true;
					}

					return false;
				},
			],
		],
		'denyCallback' => function () {
			if (Yii::$app->user->isGuest == false) {
				throw new \yii\web\NotFoundHttpException('У вас нет доступа к этой странице.');
			} else {
				return Yii::$app->response->redirect(['site/login']);
			}
		},
	],
];
