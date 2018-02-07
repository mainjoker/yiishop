<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //'defaultRoute' => 'site',
    'defaultRoute' => 'index',
    'aliases'=>[
        '@mailerqueue'=>'@vendor/mailerqueue/src',
        '@yii/redis'=>'@vendor/yiisoft/yii2-redis',

    ],
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
              'class' => 'yii\redis\Cache',
              'redis' => [
                  'hostname' => 'localhost',
                  'port' => 6379,
                  'database' => 2,
              ]
          ],
        //ES
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                //['http_address' => '127.0.0.1:9200'],
                ['http_address' => '192.168.176.128:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'q6ga0ArPuP1iWsey2H6aoeWsP7G98FnL',
        ],
      /*  'cache' => [
            'class' => 'yii\caching\FileCache',
        ],*/
        //前台用户登录组件
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>'Index/login',
            'idParam'=>'userid',
        ],
        //后台用户登录组件
        'admin'=>[
            'class'=>'yii\web\user',
            'identityClass'=>'app\modules\models\Admin',
            'enableAutoLogin'=>true,
            'loginUrl'=>'Public/login',
            'idParam'=>'adminid',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'mailerqueue\MailerQueue',//更改为自定义的重写类
            //'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'db'=>'1',
            'key'=>'mails',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.126.com',
                'username' => 'hkbmail@126.com',
                'password' => 'hkbtest123',
                'port' => '465',
                'encryption' => 'ssl',
            ],

        ],
        'qiniu'=> [ 
                'class' => 'crazyfd\qiniu\Qiniu', 
                'accessKey' => 'U2ul6LJzDA5jeCbslCsRc5kWkuMYEotUHQIFFf0T', 
                'secretKey' => 'BIcB9uERPp3d1ULz5WfFvSrJ09H81uXQG69lJVHh', 
                'domain' => 'ozv0ga5al.bkt.clouddn.com', 
                'bucket' => 'yiishop', 
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
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>".html",
            'rules' => [
                'detail/<productid:\d+>'=>'product/detail',
                'list/<cateid:\d+>'=>'product/list',
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => [$_SERVER['REMOTE_ADDR']],
    ];
    $config['modules']['admin'] = [
        'class' => 'app\modules\admin',
    ];
}

return $config;
