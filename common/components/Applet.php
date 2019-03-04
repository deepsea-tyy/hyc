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
				a.bid={$this->bid}
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
	 * 通过商户id设置
	 */
	public function setConfigById($id=1)
	{
		$prefix = Yii::$app->db_applet->tablePrefix;
		$res = Business::find()->select('id,btoken,access_token,expires_in')->where(['id'=>$id])/*->asArray()*/->one();
		if (empty($res)) return false;
		$setting = [];
		$setting = Yii::$app->db_applet->createCommand("SELECT 
				b.skey,b.value
				FROM
				{$prefix}business_data AS a ,{$prefix}setting AS b
				WHERE
				a.bid={$id}
				AND a.tab_name='setting'
				AND FIND_IN_SET(b.id,a.items)
				AND b.skey LIKE 'wx_applet%'")
			->queryAll();

		$this->bid = $res->id;
		$this->access_token = $res->access_token;
		$this->expires_in = $res->expires_in;
		$k = array_column($setting, 'skey');
		$v = array_column($setting, 'value');
		$setting = array_combine($k, $v);
		$this->setConfig($setting['wx_applet_appid'],$setting['wx_applet_app_secret'],$setting['wx_applet_encodingaeskey'],$res->btoken);
		return true;
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

	/**
	 * 获取后端访问凭证
	 */
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
			    if (isset($data['errcode']) && $data['errcode']) return false;

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

	/**
	 * 发送客服消息
	 */
	public function sendCustomerMessage($data=[])
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->getAccessToken();
    	$client = new Client(['formatters' => [
		        Client::FORMAT_JSON => [
		        	'class' => 'yii\httpclient\JsonFormatter',
		        	'encodeOptions' => JSON_UNESCAPED_UNICODE
		        ],
		    ],
		]);
		$response = $client->createRequest()
    		->setFormat(Client::FORMAT_JSON)
		    ->setMethod('POST')
		    ->setUrl($url)
		    ->setData($data)
		    ->send();
		if ($response->isOk) {
		    $data = $response->data;
		    if (isset($data['errcode']) && $data['errcode'] != 0) return false;
		}
		return true;
	}

    /**
     * 下载图片
     */
    public function getTempMedia($mediaId)
    {
    	$url = 'https://api.weixin.qq.com/cgi-bin/media/get';
    	$data = [
    		'access_token'=> $this->getAccessToken(),
    		'media_id' => $mediaId
    	];
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('GET')
		    ->setUrl($url)
		    ->setData($data)
		    ->send();
	    $type = $response->headers['content-type'];
	    $ext = strpos($type, 'jpeg') !== false ? '.jpeg' :'';
	    if (!$ext) $ext = strpos($type, 'png') !== false ? '.png' :'';

	    $rootPath = Yii::getAlias('@frontend') . '/web/file/applet';
	    $content = $response->content;
	    if (!file_exists($rootPath))  mkdir($rootPath, 0777, true);
	    $filePath = '/' . Yii::$app->applet->bid . '-' . time() . $ext;
	    file_put_contents($rootPath . $filePath, $content);
	    return '/file/applet/' . $filePath;
    }

    /**
     * 上传文件
     */
    public function uploadTempMedia($file)
    {
    	$url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->getAccessToken() . '&type=image';
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
		    ->setUrl($url)
		    ->addFile('media', $file)
		    ->send();
	    if ($response->isOk) {
		    $data = $response->data;
		    // var_dump($data);
		    if (isset($data['errcode']) && $data['errcode'] != 0) return false;
		}
		return true;
    }











}