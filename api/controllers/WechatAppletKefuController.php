<?php
namespace api\controllers;

use Yii;
use api\common\controllers\BaseController;
use yii\helpers\Url;
use common\models\applet\WeixinAppletMessage;
use api\models\WechatAppletKefuForm;

class WechatAppletKefuController extends BaseController
{
  /**
   * 商户客服发送消息
   */
  public function actionSendmsg()
  {
    $form = new WechatAppletKefuForm();
    if ($form->load(Yii::$app->request->post(),'') && $form->validate()) {
      // json_encode([$content],JSON_UNESCAPED_UNICODE)
      $s_uid = 1;
      Yii::$app->applet->setConfigById($s_uid);
      $res = Yii::$app->runAction('applet/reply',['touser'=>$form->touser, 'content'=>$form->content,'type'=>$form->type]);
      if ($res) {
        $model = new WeixinAppletMessage();
        $model->content = $form->content;
        $model->bid = $s_uid;
        $model->touser = $form->touser;
        $model->fromuser = $s_uid;
        $model->ctime = time();
        $model->save();
        return $this->success();
      }
      return $this->fail('发送失败');
    }
    return $this->fail('参数有误',$model);
  }

}
