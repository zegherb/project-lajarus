<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'container' => [
        'singletons' => [
            \yii\mail\MailerInterface::class => [
                'class' => \yii\symfonymailer\Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath' => '@app/mail',
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5vYHuTtXW4hqgFQovSDsk5higvVuRNr2',
            'parsers' => [
            'application/json' => 'yii\web\JsonParser',
            ],
        ],

        

        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => \yii\mail\MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'register' => 'site/signup',
                'login' => 'site/login',
                'user/dashboard' => 'user/dashboard',
                'POST api/register' => 'api/register',
                'POST api/login' => 'api/login',
                'user/new-report' => 'user/new-report',
                'POST api/create-laporan' => 'api/create-laporan',
                'user/report/<id:\d+>' => 'user/report-detail',
                'user/update-report/<id:\d+>' => 'user/update-report',
                'POST api/update-laporan/<id:\d+>' => 'api/update-laporan',
                'admin/login' => 'admin/login',
                'admin/dashboard' => 'admin/dashboard',
                'POST api/admin-login' => 'api/admin-login',
                'admin/report-detail/<id:\d+>' => 'admin/report-detail',
                'POST api/admin-update-report/<id:\d+>' => 'api/admin-update-report',
                'POST api/add-komentar/<id:\d+>' => 'api/add-komentar',
                'POST api/admin-delete-report/<id:\d+>' => 'api/admin-delete-report',
                'POST api/delete-komentar/<id:\d+>' => 'api/delete-komentar',
                'admin/statistik' => 'admin/statistik',
                'admin/reports' => 'admin/reports',
                'admin/users' => 'admin/users',
                'POST api/make-admin/<id:\d+>' => 'api/make-admin',
                'POST api/delete-user/<id:\d+>' => 'api/delete-user',
                'admin/settings' => 'admin/settings',
                'POST admin/update-profile' => 'admin/update-profile',
                'POST admin/change-password' => 'admin/change-password',
                'soap/index' => 'soap/index',
                'admin/riwayat-sistem' => 'admin/riwayat-sistem',

            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
