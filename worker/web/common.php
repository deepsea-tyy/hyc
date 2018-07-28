<?php
// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/../../common/config/bootstrap.php';
require_once __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require_once __DIR__ . '/../../common/config/main.php',
    require_once __DIR__ . '/../../common/config/main-local.php',
    require_once __DIR__ . '/../config/main.php',
    require_once __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->init();


