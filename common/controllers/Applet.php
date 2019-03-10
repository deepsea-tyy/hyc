<?php

namespace common\controllers;

use Yii;
use common\models\Chat;
use GatewayClient\Gateway;

/**
 * 小程序操作
 */
class Applet extends \yii\web\Controller
{
	public function init()
	{
		Gateway::$registerAddress = Yii::$app->params['workerConfig']['registerAddress'];
	}
	/**
	 * 接收数据
	 */
    public function actionReceive($xml)
    {
		$doc = new \DOMDocument();
		$doc->loadXML((string)$xml);
		$msgType = $doc->getElementsByTagName('MsgType');
		if ($msgType??'') {
			$openid = $doc->getElementsByTagName('FromUserName')->item(0)->nodeValue;
    		$prefix = Yii::$app->db_applet->tablePrefix;
			$row = Yii::$app->db_applet->createCommand("SELECT 
				user_id
				FROM
				{$prefix}user_wx 
				WHERE
				openid='{$openid}'")
				->queryOne();
			$uuid = Yii::$app->user->identityClass::getUuidBySubsystem($row['user_id'],'wechat_applet',2);

			$where = [
				'create_at' => $doc->getElementsByTagName('CreateTime')->item(0)->nodeValue,
				'bid'   => Yii::$app->applet->bid,
				'fromuser' => $uuid,
				'touser' => Yii::$app->applet->uuid
			];
			$row = Chat::find()->where($where)->one();
			if ($row) return;//接收过滤
			$msgType = $msgType->item(0)->nodeValue;
			switch ($msgType) {
				case 'text':
					$where['type'] = 1;
					$where['content'] = $doc->getElementsByTagName('Content')->item(0)->nodeValue;

					break;
				case 'image':
					$where['type'] = 2;
					$mediaId = $doc->getElementsByTagName('MediaId')->item(0)->nodeValue;
					$where['content'] = Yii::$app->applet->getTempMedia($mediaId);

					break;
				case 'miniprogrampage':
					$where['type'] = 1;
					// echo "$mediaId";
					break;
				
				default:
					return;
					break;
			}

			$model = new Chat();
			$where['platform'] = 1;
			$model->load($where,'');

			if (!YII_DEBUG) $model->save();
			$res['type'] = 'wechat_applet_kefu';
			$res['message'] = '微信小程序客户消息';
			$res['data']['message'] = $where['content'];
			$res['data']['fromuser'] = $where['fromuser'];
			$res['data']['touser'] = $where['touser'];
			Gateway::sendToUid(Yii::$app->applet->uuid, json_encode($res,JSON_UNESCAPED_UNICODE));
		}
    }

    /**
     * 发送客服消息
     */
    public function actionReply($touser,$content,$type=1)
    {
    	$data = ['touser' => $touser, 'msgtype' => $type];
    	switch ($type) {
    		case '1':
    			$data['text'] = ['content'=> $content];
    			break;
    		case '2':
    			$data['image'] = ['media_id'=> $content];
    			break;
    		case '3':
    			$data['link'] = json_decode($content,true);
    			break;
    		case '4':
    			$data['miniprogrampage'] = json_decode($content,true);
    			break;
    		
    		default:
    			return false;
    			break;
    	}
    	return Yii::$app->applet->sendCustomerMessage($data);
    }


}
