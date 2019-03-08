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
	if (empty($xmldata)) die('未接受到数据');
	file_put_contents(\Yii::getAlias('@logpath') . '/info.log', $xmldata,FILE_APPEND);//记录xml
	if ($encrypt_type) {
		/*$xmldata = '<xml>
    <ToUserName><![CDATA[gh_28b705fe713d]]></ToUserName>
    <FromUserName><![CDATA[oxE4g5aFAwfJ-Uo6fZGYPBoT-FnM]]></FromUserName>
    <CreateTime>1551778654</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[啦啦啦]]></Content>
    <MsgId>22216127643609599</MsgId>
    <Encrypt><![CDATA[r8pc73My7Z9PO4RC9hWb3NFXwYoWyaPgYV1khzapck6tCpkQhqwi35IzX2Qr1MB6LSDAcWT+1Dz31CNOBULJPU4BYuLSNRRsvOIr2jFPC4v5fXWALNm1GyBLxLbx0y0mT1xVu/KsdWZ4qBDU5UcoBNmtBsrmen9q3d3qlpwHBGKPyUh1JawFJARQzELBQn4MvqsB6CB+esy84dQCKPYrSnvFQ0+fsmUMuLhRMoKzX3mls32UVTOYcopfvQOH4wkbKTFZ0cyA6Z/iiCZSMr1CQdPK5FwdroAqbR2KiewimcmUWlP9uuiB2ap686q3+Oaz4rL7O4l+G1YHIUAOZ9lv+B50bqRq1BmGVwavIsYU/AbGTIxEd0H0O3ShJZypFtSWavZ+q47Rsp980kCOizYNp5K1IAvS9a3o4PufITmysUo=]]></Encrypt>
</xml>';*/
		$xmldata = Yii::$app->applet->decrypt($xmldata, $timestamp, $nonce, $msg_signature);
	}
	Yii::$app->runAction('applet/receive',['xml'=>$xmldata]);
	die('success');
}
else
$app->run();
