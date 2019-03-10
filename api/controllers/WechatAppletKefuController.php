<?php
namespace api\controllers;

use Yii;
use api\common\controllers\BaseController;
use yii\helpers\Url;
use common\models\Chat;
use common\models\SubsystemIdentity;
use common\models\applet\BusinessData;
use common\models\applet\UserWx;
use api\models\WechatAppletKefuForm;

class WechatAppletKefuController extends BaseController
{
  public $uuid;
  public $bid;
  public function setBusiness()
  {
    $si = Yii::$app->user->identityClass::getSidByAccessToken(Yii::$app->request->get('access-token',0),'wechat_applet',1); //系统身份
    $this->uuid = $si->uuid;
    $this->bid = $si->s_uid;
  }

  /**
   * 商户客服发送消息
   */
  public function actionSendmsg()
  {
    $this->setBusiness();
    $model = new Chat();
    $model->load(Yii::$app->request->post(),'');
    Yii::$app->applet->setConfigById($this->bid);

    $si = SubsystemIdentity::find()->where(['uuid'=>$model->touser])->asArray()->one();
    $uwx = UserWx::find()->select('openid')->where(['user_id'=>$si['s_uid']])->asArray()->one();

    //1文本2微信媒体图片3图片链接4卡片
      switch ($model->type) {
        case 2:
          $model->type = 2;
          
          break;
        case 3:
          $model->type = 3;
          // json_encode([$content],JSON_UNESCAPED_UNICODE)
          
          break;
        case 4:
          $model->type = 4;
          
          break;
        
        default:
          $model->type = 1;
          break;
      }
      return $this->success($uwx);
    $res = Yii::$app->runAction('applet/reply',['touser'=>$uwx['openid'], 'content'=>$model->content,'type'=>$model->type]);
    if ($res) {
      $model->create_at = time();
      $model->fromuser = $this->uuid;
      $model->save();
      return $this->success();
    }
    return $this->fail('发送失败');
  }

  /**
   * 客服对话列表
   */
  public function actionDialoguelist()
  {
    $this->setBusiness();

    $customer = BusinessData::find()->select('items')->where(['bid'=>$this->bid, 'tab_name'=>'user'])->one();
    if (!$customer) return $this->success();

    $res = SubsystemIdentity::find()->select('uuid,s_uid')
      ->where(['s_uid'=>explode(',', $customer['items']),'s_name'=>'wechat_applet','type'=>2])
      ->all();
    $identity = array_column($res, 'uuid');
    $arr = array_combine($identity, array_column($res, 's_uid'));

    $chat = Chat::find()
      ->select('type,fromuser,touser,content,create_at')
      ->where(['status'=>0,'fromuser'=>$identity,'touser'=>$this->uuid])
      ->groupBy('fromuser,touser')
      // ->createCommand()->getRawSql();
      ->asArray()
      ->all();

    if ($chat) {
      foreach ($chat as &$v) {
        $info = UserWx::find()->select('avatar,nickname')->where(['user_id'=>$arr[$v['fromuser']]])->one();
        $v['avatar'] = $info['avatar'];
        $v['nickname'] = $info['nickname'];
      }
    }
    return $this->success($chat);
  }

  /**
   * 对话消息
   */
  public function actionDialoguemsg()
  {
    $this->setBusiness();
    $fromuser = Yii::$app->request->post('fromuser');
    $list = Chat::find()
      ->where(['or',['fromuser'=>$fromuser,'touser'=>$this->uuid],['fromuser'=>$this->uuid,'touser'=>$fromuser]])
      // ->createCommand()->getRawSql();
      ->all();
    if (!YII_DEBUG) Chat::updateAll(['status'=>1],['fromuser'=>$fromuser,'touser'=>$this->uuid]);
    return $this->success(array_reverse($list));
  }

  
}
