<?php
namespace common\components;

use Yii;
use yii\base\Component;
use common\models\applet\Business;
use yii\httpclient\Client;

/**
 * 小程序组件
 */
class Applet extends Component
{
	public $appid;
	public $originalId;
	public $appSecret;
	public $encodingAesKey;
	public $token;
	public $access_token;
	public $expires_in;
	public $bid;//商户id

	/**
	 * 请求签名验证
	 */
	public function checkSignature($signature,$timestamp,$nonce,$token=null)
	{
		$arr = [$token ?? $this->token, $timestamp, $nonce];
		sort($arr, SORT_STRING);
		$str = sha1(implode($arr));
		return $str == $signature;
	}

	/**
	 * 设置小程序配置
	 */
	public function setConfig($appid='', $appSecret='', $encodingAesKey='', $token='')
	{
		$this->appid = $appid;
		$this->appSecret = $appSecret;
		$this->encodingAesKey = $encodingAesKey;
		$this->token = $token;
	}

	/**
	 * 通过签名设置对应商户
	 */
	public function setConfigBySignature($signature,$timestamp,$nonce)
	{
		$list = Business::find()->select('id,btoken,access_token,expires_in')/*->asArray()*/->all();
		$prefix = Yii::$app->db_applet->tablePrefix;
		$setting = [];
		foreach ($list as $v) {
			if ($this->checkSignature($signature,$timestamp,$nonce,$v->btoken)) {
				$this->token = $v->btoken;
				$this->bid = $v->id;
				$this->access_token = $v->access_token;
				$this->expires_in = $v->expires_in;
				$setting = Yii::$app->db_applet->createCommand("SELECT 
				b.skey,b.value
				FROM
				{$prefix}business_data AS a ,{$prefix}setting AS b
				WHERE
				a.bid=1
				AND a.tab_name='setting'
				AND FIND_IN_SET(b.id,a.items)
				AND b.skey LIKE 'wx_applet%'")
				->queryAll();
				break;
			}
		}
		if (!empty($this->token)) {
			$k = array_column($setting, 'skey');
			$v = array_column($setting, 'value');
			$setting = array_combine($k, $v);
			$this->setConfig($setting['wx_applet_appid'],$setting['wx_applet_app_secret'],$setting['wx_applet_encodingaeskey'],$this->token);
			return true;
		}
		return false;
	}


	/**
	 * 通过原始设置对应商户
	 */
	public function setConfigByOriginalId($originalId='gh_28b705fe713d')
	{
		$prefix = Yii::$app->db_applet->tablePrefix;
		$res = Yii::$app->db_applet->createCommand("SELECT 
		b.bid,c.btoken,b.items
		FROM
		{$prefix}setting AS a,{$prefix}business_data AS b,{$prefix}business AS c
		WHERE
		a.skey='wx_applet_original_id' 
		AND a.`value`='$originalId' 
		AND b.tab_name='setting' 
		AND FIND_IN_SET(a.id,b.items)
		AND b.bid=c.id")
			->queryAll();
		$in = $res[0]['items'];
		$setting = Yii::$app->db_applet->createCommand("SELECT 
		* 
		FROM
		jshop_setting
		WHERE 
		FIND_IN_SET(id,'$in')")
			->queryAll();

		if (empty($in = $res[0]['btoken'])) return false;
		$this->token = $res[0]['btoken'];
		$k = array_column($setting, 'skey');
		$v = array_column($setting, 'value');
		$setting = array_combine($k, $v);
		$this->setConfig($setting['wx_applet_appid'],$setting['wx_applet_app_secret'],$setting['wx_applet_encodingaeskey'],$this->token);

		return $setting;
	}

	/**
	 * 解密xml
	 */
	public function decrypt($xml, $timestamp, $nonce, $msgSignature)
	{
		require Yii::getAlias('@rootpath') . '/wxpush/wxBizMsgCrypt.php';
		$pc = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appid);
		$msg = '';
		$pc->decryptMsg($msgSignature, $timestamp, $nonce, $xml, $msg);
		return $msg;
	}

	/**
	 * 加密xml
	 */
	public function encrypt($xml, $timestamp, $nonce)
	{
		require Yii::getAlias('@rootpath') . '/wxpush/wxBizMsgCrypt.php';
		$pc = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appid);
		$encryptMsg = '';
		$pc->encryptMsg($xml, $timeStamp, $nonce, $encryptMsg);
		return $encryptMsg;
	}

	public function getAccessToken()
	{
		if (time() > $this->expires_in) {
			$url = 'https://api.weixin.qq.com/cgi-bin/token';
			$client = new Client();
			$response = $client->createRequest()
		    ->setMethod('GET')
		    ->setUrl($url)
		    ->setData(['grant_type' => 'client_credential', 'appid' => $this->appid,'secret'=>$this->appSecret])
		    ->send();
			if ($response->isOk) {
			    $data = $response->data;
			    if (isset($data['errcode']) && $data['errcode']) {
			    	return false;
			    }

			    //更新access_token
			    $b = Business::findOne($this->bid);
			    $b->access_token = $data['access_token'];
			    $b->expires_in = time() + $data['expires_in'];
			    $a = $b->save();
			    $this->access_token = $data['access_token'];
			}
		}
		return $this->access_token;
	}




















}