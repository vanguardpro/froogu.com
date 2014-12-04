<?php
if(file_exists(__DIR__ . '/local.php')){
  $local = require(__DIR__ . '/local.php');
  $dns=$local['mongodbDsn'];
}
else{
    $dns="mongodb://productsUserAdmin:Djpoekr;wd@localhost:27017/products";
}
$params = require(__DIR__ . '/params.php');


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
    'request' => [
        'enableCookieValidation' => true,
        'enableCsrfValidation' => true,
        'cookieValidationKey' => 'xxxxxxx',
      ],
    
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://productsUserAdmin:Djpoekr;wd@localhost:27017/products',
        ],
   
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
