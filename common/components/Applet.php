<?php
namespace common\components;

use Yii;
use yii\base\Component;
use common\models\applet\Business;

/**
 * 小程序组件
 */
class Applet extends Component
{
	public $appId;
	public $originalId;
	public $appSecret;
	public $encodingAesKey;
	public $token;
	// public $timestamp;
	// public $nonce;
	// public $msg_signature;
	// public $echostr;

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
	public function setConfig($appId='', $appSecret='', $encodingAesKey='', $token='')
	{
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->encodingAesKey = $encodingAesKey;
		$this->token = $token;
	}

	/**
	 * 通过签名设置对应商户
	 */
	public function setConfigBySignature($signature,$timestamp,$nonce)
	{
		$list = Business::find()->select('id,btoken')/*->asArray()*/->all();
		$prefix = Yii::$app->db_applet->tablePrefix;
		$setting = [];
		foreach ($list as $v) {
			if ($this->checkSignature($signature,$timestamp,$nonce,$v->btoken)) {
				$this->token = $v->btoken;
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
				// echo "<pre>";
				// return $setting;
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
	public function setConfigByOriginalId($id)
	{
		$prefix = Yii::$app->db_applet->tablePrefix;
		$res = Yii::$app->db_applet->createCommand("SELECT 
		b.bid,c.btoken,b.items
		FROM
		{$prefix}setting AS a,{$prefix}business_data AS b,{$prefix}business AS c
		WHERE
		a.skey='wx_applet_original_id' 
		AND a.`value`='gh_28b705fe713d' 
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
		$pc = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appId);
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
		$pc = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appId);
		$encryptMsg = '';
		$pc->encryptMsg($xml, $timeStamp, $nonce, $encryptMsg);
		return $encryptMsg;
	}

}