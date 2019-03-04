<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\httpclient\Client;
use common\models\applet\WeixinAppletMessage;

/**
 * 小程序操作
 */
class AppletController extends \yii\web\Controller
{
	/**
	 * 接收数据
	 */
    public function actionReceive($xml)
    {
		$doc = new \DOMDocument();
		$doc->loadXML((string)$xml);
		$msgType = $doc->getElementsByTagName('MsgType');
		if ($msgType??'') {
			$msgType = $msgType->item(0)->nodeValue;
			$data = [
				'ctime' => $doc->getElementsByTagName('CreateTime')->item(0)->nodeValue,
				'bid'   => Yii::$app->applet->bid,
				'touser'=> $doc->getElementsByTagName('FromUserName')->item(0)->nodeValue,
			];
			$row = WeixinAppletMessage::find()->where($data)->one();
			if ($row) return;
			switch ($msgType) {
				case 'text':
					$data['type'] = 1;
					$data['content'] = $doc->getElementsByTagName('Content')->item(0)->nodeValue;

					break;
				case 'image':
					$data['type'] = 2;
					$mediaId = $doc->getElementsByTagName('MediaId')->item(0)->nodeValue;
					$data['content'] = Yii::$app->applet->getTempMedia($mediaId);

					break;
				case 'miniprogrampage':
					$data['type'] = 1;
					// echo "$mediaId";
					break;
				
				default:
					
					break;
			}
			$model = new WeixinAppletMessage();
			$model->load($data,'');
			$model->save();
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
    			# code...
    			break;
    	}
    	return Yii::$app->applet->sendCustomerMessage($data);
    }


}
