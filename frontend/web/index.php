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
    <CreateTime>1551515365</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[测试一下]]></Content>
    <MsgId>22212358231455240</MsgId>
    <Encrypt><![CDATA[0Qxc8US38it1DFBXEXFp3KhTdEDbvuyRFK3zKo2KP7IVw3bdQrwsCkJe3aQNlH1cw9IW2WverONyoQOJg/3hx/uKl1Ci1nh7Lv0DnURmddtkBHTM3swKMejZhotcktJyR9jl8nzWSqPHKOMHmJaRwgCeIm5HA/3wd+8C7DRr0vsymHwE1QUcsscEoPGK72V9SOnHQlDTKlvlo3fXYWyRQo8HSkianYOJyRFiIvb1u3BW/N26/soM3O7cJ+en4ydspJnfXcAMpy44UYTonDU1sv2c4r6X9PCeKTn6YnlaCjneTAPlJENtkIy2PSX/NosUR9wEiCNWooj9KesKlHOtvW2npkOwr5yrOMnH7ud8Jp1ojBEJ00ty6JGg4oiPcTxlworOzjj+4JHypZLdnZPoXBNx85adp2xgsUZBesHpRbyJNy5IH+hEBdqEzhmUNOFKbgm2hxCUGFnRXPzWGBj+/Q==]]></Encrypt>
</xml>';
		$xmldata = Yii::$app->applet->decrypt($xmldata, $timestamp, $nonce, $msg_signature);
	}
	Yii::$app->runAction('index/receive',['xml'=>$xmldata]);
	die('success');
	// 小程序操作
}
else
$app->run();
