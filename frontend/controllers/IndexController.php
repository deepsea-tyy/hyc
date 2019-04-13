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
        // return $this->asJson($data);
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
