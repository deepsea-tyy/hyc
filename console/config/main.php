<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'queue2'
    ],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'serializer' => \yii\queue\serializers\JsonSerializer::class,
            'redis' => [
                'class' => \yii\redis\Connection::class,
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
                // retry connecting after connection has timed out
                // yiisoft/yii2-redis >=2.0.7 is required for this.
                'retries' => 3,
            ], // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
        ],
        'queue2' => [
            'class' => \yii\queue\redis\Queue::class,
            'serializer' => \yii\queue\serializers\JsonSerializer::class,
            'redis' => [
                'class' => \yii\redis\Connection::class,
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 2,
                // retry connecting after connection has timed out
                // yiisoft/yii2-redis >=2.0.7 is required for this.
                'retries' => 3,
            ], // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
        ],
        'memcached' => [
            'class' => 'yii\caching\MemCache',
            /*'servers' => [
                [
                    'host' => 'localhost',
                    'port' => 11211,
                    'weight' => 100,
                ],
                [
                    'host' => 'localhost',
                    'port' => 11211,
                    'weight' => 50,
                ],
            ],*/
            'useMemcached' => true ,
        ],
        'crawler_detect' => [
            'class' => 'alikdex\crawlerdetect\CrawlerDetect',
            'setParams' => true, // optional, bootstrap initialize requred
        ],

        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://@localhost:27017/spider',
            'options' => [
                "username" => "tyy",
                "password" => "123456"
            ]
        ],
    ],
    'params' => $params,
];
