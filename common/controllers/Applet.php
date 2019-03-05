<?php

namespace common\controllers;

use Yii;
use common\models\applet\WeixinAppletMessage;
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
			$where = [
				'ctime' => $doc->getElementsByTagName('CreateTime')->item(0)->nodeValue,
				'bid'   => Yii::$app->applet->bid,
				'fromuser'=> $doc->getElementsByTagName('FromUserName')->item(0)->nodeValue,
			];
			$row = WeixinAppletMessage::find()->where($where)->one();
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
			$model = new WeixinAppletMessage();
			$model->load($where,'');
			$model->save();
			$res['type'] = 'wechat_applet_kefu';
			$res['msg'] = $where['content'];
			Gateway::sendToUid(Yii::$app->applet->uid, json_encode($res,JSON_UNESCAPED_UNICODE));
		}
    }

    /**
     * 发送客服消息
     */
    public function actionReply($touser,$content,$type='text')
    {
    	$data = ['touser' => $touser, 'msgtype' => $type];
    	switch ($type) {
    		case 'text':
    			$data['text'] = ['content'=> $content];
    			break;
    		case 'image':
    			$data['image'] = ['media_id'=> $content];
    			break;
    		case 'link':
    			$data['link'] = json_decode($content,true);
    			break;
    		case 'miniprogrampage':
    			$data['miniprogrampage'] = json_decode($content,true);
    			break;
    		
    		default:
    			return;
    			break;
    	}
    	return Yii::$app->applet->sendCustomerMessage($data);
    }


}
