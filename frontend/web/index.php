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
	if (!YII_DEBUG) {
		if (empty($xmldata)) die('未接受到数据');
		file_put_contents(\Yii::getAlias('@logpath') . '/info.log', $xmldata,FILE_APPEND);//记录xml
	}else{
		$xmldata = '<xml>
    <ToUserName><![CDATA[gh_28b705fe713d]]></ToUserName>
    <FromUserName><![CDATA[oxE4g5aFAwfJ-Uo6fZGYPBoT-FnM]]></FromUserName>
    <CreateTime>1552270809</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[再来]]></Content>
    <MsgId>22223177791322242</MsgId>
    <Encrypt><![CDATA[gPlmKMOuFDwkhtqvRQfTyECym+AePaYWsY5haYMHye2crcQ84AOcxtNgzNLhDn6pq8+Ge+kgyEdq9pXQErt1pnONvO5RVQD33Cwt8OeA0GZWTjEOCkRPcxKehU+LNr7EyPnt8HI30t/vYFhJ6LqIEowoCd4NDTEyV680zq/dDLdrJhaFggdb8kmZ4kNmRnxBx7ORtXpsuk/m9S+YULe9xnQmuKXklVbFeIB+AXWxhT1qcGTrGSrkQZA7gU63tzCeoP7V8w+8R2DexKu2SIPa14+DwIp5wK8yntIokoYvty8vCGgv7aerzoyhyYjK6AmkC/ipu/KPz1dTQ6TJma7bMvMc9UwlMjTTgpCpx1VSNgtITsXm9K71/0LLvtTx/jncE2QZ/6P0D/wwK4NgZWzlDzrmhvLBlZnGqqYzMFE09Nw=]]></Encrypt>
</xml>';
	}
	if ($encrypt_type) {
		$xmldata = Yii::$app->applet->decrypt($xmldata, $timestamp, $nonce, $msg_signature);
	}
	Yii::$app->runAction('applet/receive',['xml'=>$xmldata]);
	die('success');
}
else
$app->run();
