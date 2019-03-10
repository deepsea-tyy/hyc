<?php

namespace frontend\controllers;

use Yii;
use common\models\applet\WeixinAppletMessage;


class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	// Yii::$app->applet->setConfigById(1);
		// $res = Yii::$app->applet->uploadTempMedia(Yii::getAlias('@frontend') . '/web/2.png');
    	// $tmp = Yii::$app->applet->getTemplateLibraryById('AT0002');
    	// $tmp = Yii::$app->applet->addTemplate('AT0002',[3,4,5]);
    	// $tmp = Yii::$app->applet->getTemplateList();
    	// $msg = WeixinAppletMessage::findOne(1);
    	// $msg['touser'] = $msg->touser;
    	// $content = '我是tyy';
    	// $tmp = Yii::$app->applet->sendTemplateMessage([
    	// 	'touser'=>$msg['touser'],
    	// 	'template_id'=>'OxvjCmM8PPhgtqjZZao_E8wz9OyFSH04itrWQIk-y6o',
    	// 	'page'=>'',
    	// 	'form_id'=>'',
    	// 	'data' => [],
    		
    	// ]);
    	
        // var_dump($user->id);exit();
        
    	return $this->asJson($tmp);
        // return $this->render('index');
    }


    public function sendmsg()
    {
    	
    	Yii::$app->applet->setConfigById(1);
    	
    	if ($msg['status']) return ;

		$res = Yii::$app->runAction('applet/reply',['touser'=>$msg->touser, 'content'=>json_encode([$content])]);
		if ($res) {
			$msg->status = 1;
			$msg->save();
			$model = new WeixinAppletMessage();
			$model->load(['content'=>$content,'ctime'=>time(),'pid'=>$msg->id,'bid'=>$msg->bid],'');
			$model->save();
		}
    }

    public function actionChat()
    {
        $this->layout = false;
        $s_uid = 1;
        $user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($s_uid,'wechat_applet',1);//获取全局身份
        return $this->render('chat',['token'=>$user->access_token,'uuid'=>$user->id]);
    }
}
