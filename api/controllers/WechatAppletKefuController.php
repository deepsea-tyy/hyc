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
      $s_uid = $form->s_uid;
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

  /**
   * 客服对话列表
   */
  public function actionDialoguelist()
  {
    $s_uid = (int)Yii::$app->request->post('s_uid',0);
    if (!$s_uid) return $this->fail();
    $prefix = Yii::$app->db_applet->tablePrefix;
    $list = Yii::$app->db_applet->createCommand("SELECT 
    b.openid,b.avatar,b.type,b.status 
    FROM
    {$prefix}business_data AS a,{$prefix}user_wx AS b
    WHERE a.bid={$s_uid} AND a.tab_name='user' AND FIND_IN_SET(b.user_id,a.items)
    ")->queryAll();
    return $this->success($list);
  }

  /**
   * 对话消息
   */
  public function actionDialoguemsg()
  {
    $fromuser = Yii::$app->request->post('fromuser');
    $touser = Yii::$app->request->post('touser');
    $list = Yii::$app->db_applet->createCommand("SELECT * 
    FROM jshop_weixin_applet_message
    WHERE
    bid={$fromuser}
    AND (fromuser={$fromuser} AND touser='{$touser}') OR (fromuser='{$touser}' AND touser={$fromuser})
    ORDER BY ctime DESC LIMIT 0,15
    ")->queryAll();
    return $this->success(array_reverse($list));
  }

  /**
   * 消息状态
   */
  public function actionReadmsg()
  {
    $fromuser = Yii::$app->request->post('fromuser');
    $touser = Yii::$app->request->post('touser');
    WeixinAppletMessage::updateAll(['status'=>1],['fromuser'=>$fromuser,'touser'=>$touser]);
    return $this->success();
  }


}
