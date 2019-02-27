<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

/*小程序接入*/
$signature = $_GET['signature']??'';
$timestamp = $_GET['timestamp']??'';
$nonce = $_GET['nonce']??'';
$echostr = $_GET['echostr']??'';
if ($signature && $timestamp && $nonce && $echostr) {
	(new yii\web\Application($config))->init();
	$query = new \yii\db\Query();
	$data = $query->select('id,btoken')
	->from( Yii::$app->db_applet->tablePrefix . 'business')
	->where(['applet'=>0])
	->all(Yii::$app->db_applet);

    foreach ($data as $row) {
	    $tmpArr = array($row['btoken'], $timestamp, $nonce);
	    sort($tmpArr, SORT_STRING);
	    $tmpStr = implode( $tmpArr );
	    $tmpStr = sha1( $tmpStr );

	    if ($tmpStr == $signature ) {
	    	Yii::$app->db_applet->createCommand()
	    	->update( Yii::$app->db_applet->tablePrefix . 'business',['applet'=>1],['id' => $row['id']])
	    	->execute();
	        die($echostr);
	    }
    }
    die('接入失败');
}

(new yii\web\Application($config))->run();
