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

$signature = $_GET['signature']??'';
$timestamp = $_GET['timestamp']??'';
$nonce = $_GET['nonce']??'';
$echostr = $_GET['echostr']??'';
$openid = $_GET['openid']??'';
$encrypt_type = $_GET['encrypt_type']??'';
$msg_signature = $_GET['msg_signature']??'';

$app = (new yii\web\Application($config));
if ($signature && $nonce) {
	$app->init();
	$res = Yii::$app->applet->setConfigBySignature($signature,$timestamp,$nonce);
	if (!$res) die('验证签名失败');
	/*商户接入小程序*/
	if ($echostr) {
	    Yii::$app->db_applet->createCommand()
		    	->update( Yii::$app->db_applet->tablePrefix . 'business',['applet'=>1],['token' => Yii::$app->applet->token])
		    	->execute();
	    die($echostr);
	}

	/*商户接收小程序回调*/
	$xmldata=file_get_contents('php://input');
	// if (empty($xmldata)) die('未接受到数据');
	file_put_contents(\Yii::getAlias('@logpath') . '/info.log', $xmldata,FILE_APPEND);
	if ($encrypt_type) {
		$xmldata = '<xml>
    <ToUserName><![CDATA[gh_28b705fe713d]]></ToUserName>
    <FromUserName><![CDATA[oxE4g5aFAwfJ-Uo6fZGYPBoT-FnM]]></FromUserName>
    <CreateTime>1551435853</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[哈哈哈哈哈哈]]></Content>
    <MsgId>22211218036310763</MsgId>
    <Encrypt><![CDATA[YqzN4kFDoeDKJHoM/xaNg2psyVHboMzMfK+0jm5SzxKU9tqPnBtaF/v177HrMMDSyp2d70lKxkIIVnwoy/Mbx0+nOjf9JjMsWJXG37PqEpwX81/0gYm8DdrbvCwznQTHQHkdfEnQ0qTIYcTvTjdxkBTfqY5b5kN6h0XrvZg7J+y2lOCHKvWq1JaBwOwKtJkhhpKERTe2Prr5xe8taWGXrwpDy/NuNd4ei7WX6+8Ti9mrog14E8Bjei9/h8CC2YaoPBEmrLYrFw5Ry2UdoWPzGHDeuv9hWOOqt+3gk0eQRWPdhOzTIWSLokDjM8oa2HnAcyCHNuK08LeKQxxgISS8kvj6De2rRdU7O561V5rZmW2lF+BkHzanethRFVDvcwbVy13hNvYBaxrFtLwgfAsJnPJl39uoGx5VTnIxIbavfKgXpqoeEAqzp9pq1sBEvMlVKb3C3XEkQxDbBEelCSA1aw==]]></Encrypt>
</xml>';
		$xmldata = Yii::$app->applet->decrypt($xmldata, $timestamp, $nonce, $msg_signature);
	}
	Yii::$app->runAction('index/test',['xml'=>$xmldata]);
	die('success');
	// 小程序操作
}
else
$app->run();
