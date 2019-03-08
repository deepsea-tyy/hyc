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
	public $uid;//系统身份表示唯一id

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
    			$user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($this->bid,'wechat_applet',1);
    			$this->uid = $user->id;
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
		    // var_dump($data);
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
	    return '/file/applet' . $filePath;
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

    /**
     * 获取小程序模版列表
     */
    public function getTemplateLibraryList($page=1,$limit=20)
    {
    	$offset = ( $page - 1 ) * $limit;
    	$url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list?access_token=' . $this->getAccessToken();
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
    		->setFormat(Client::FORMAT_JSON)
		    ->setUrl($url)
		    ->setData(['offset' => $offset, 'count' => $limit])
		    ->send();
		if ($response->isOk) {
		    $data = $response->data;
		    if (isset($data['errcode']) && $data['errcode'] == 0) return $data['list'];
		}
		return false;
    }

    /**
     * 获取小程序模版详情
     */
    public function getTemplateLibraryById($id)
    {
    	$url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token=' . $this->getAccessToken();
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
    		->setFormat(Client::FORMAT_JSON)
		    ->setUrl($url)
		    ->setData(['id' => $id])
		    ->send();
		if ($response->isOk) {
		    $data = $response->data;
		    if (isset($data['errcode']) && $data['errcode'] == 0) return $data;
		}
		return false;
    }

    /**
     * 添加个人模版库列表
     */
    public function addTemplate($id,$keyword_id_list=[])
    {
    	$url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token=' . $this->getAccessToken();
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
    		->setFormat(Client::FORMAT_JSON)
		    ->setUrl($url)
		    ->setData(['id' => $id, 'keyword_id_list'=>$keyword_id_list])
		    ->send();
		if ($response->isOk) {
		    $data = $response->data;
		    if (isset($data['errcode']) && $data['errcode'] == 0) return $data;
		}
		return false;
    }


    /**
     * 获取个人模版库列表
     */
    public function getTemplateList($page=1,$limit=20)
    {
    	$offset = ( $page - 1 ) * $limit;
    	$url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token=' . $this->getAccessToken();
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
    		->setFormat(Client::FORMAT_JSON)
		    ->setUrl($url)
		    ->setData(['offset' => $offset, 'count' => $limit])
		    ->send();
		if ($response->isOk) {
		    $data = $response->data;
		    if (isset($data['errcode']) && $data['errcode'] == 0) return $data['list'];
		}
		return false;
    }

    /**
     * 发送模板消息
     */
    public function sendTemplateMessage($data=[])
    {
    	$url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $this->getAccessToken();
    	$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
    		->setFormat(Client::FORMAT_JSON)
		    ->setUrl($url)
		    ->setData($data)
		    ->send();
		if ($response->isOk) {
		    $data = $response->data;
		    if (isset($data['errcode']) && $data['errcode'] != 0) return false;
		}
		return false;
    }

}