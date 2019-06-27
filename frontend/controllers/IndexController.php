<?php

namespace frontend\controllers;

use Yii;
use common\models\applet\WeixinAppletMessage;
use yii\httpclient\Client;
use serhatozles\simplehtmldom\SimpleHTMLDom;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        /**
         * nginx 直播间权限认证 get请求
         * rtmp://localhost:1935/live/1?auth=123456
         */
        Yii::$app->response->setStatusCode(200);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = ['code'=>200,'detail'=>'SUCCESS'];
        // Yii::$app->response->data = ['code'=>500,'detail'=>'auth error'];
        Yii::$app->response->send();
    }


    public function actionWxChat()
    {
        $this->layout = false;
        $s_uid = 1;
        $user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($s_uid,'wechat_applet',1);//获取全局身份
        return $this->render('wxchat',['token'=>$user->access_token,'uuid'=>$user->id]);
    }

    public function actionChat()
    {
        $this->layout = false;
        $s_uid = 1;
        $user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($s_uid,'wechat_applet',1);//获取全局身份
        return $this->render('chat',['token'=>$user->access_token,'uuid'=>$user->id]);
    }

    public function actionCust()
    {
        $this->layout = false;
        $s_uid = 1;
        $user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($s_uid,'wechat_applet',2);//获取全局身份
        return $this->render('cust',['token'=>$user->access_token,'uuid'=>$user->id]);
    }
}
