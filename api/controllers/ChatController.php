<?php

namespace api\controllers;

use Yii;
use GatewayClient\Gateway;
use common\models\Chat;
use yii\helpers\Url;
use yii\web\UploadedFile;
/**
 * 聊天接口
 */
class ChatController extends \api\common\controllers\BaseController
{
     /**
   * 点对点发送消息
   */
  public function actionSendmsg()
  {
    $model = new Chat(['scenario' => Chat::SCENARIO_SEND_MSG]);
    // $model->scenario = Chat::SCENARIO_SEND_MSG;
    if ($model->load(Yii::$app->request->post(),'') && $model->validate()) {


      if ($model->type == 2) {
        $path = 'uploads/image/chat';

        $patch = $path . '/' . date('Ymd');
        $imgPath = '';

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $model->content, $result)){
          $type = $result[2];
          $imgPath = $path.'/'.date('Ymd',time()).'/';

          if(!file_exists($imgPath)){
              mkdir($imgPath, 0777, true);
          }
          $imgPath = $imgPath . $this->user->id .'-' . time().'.'.$type;

          if (file_put_contents($imgPath, base64_decode(str_replace($result[1], '', $model->content)))){
              $model->content = $imgPath;
          }
        }
        if (!$imgPath) return $this->fail();
      }
      $model->content = $model->type == 2 ? Url::base(true) . '/' . $model->content : $model->content;
      $model->created_at = time();
      $model->fromuser = $this->user->id;
      Gateway::sendToUid($model->touser, json_encode([
        'type'=>'chat_message',
        'data'=>[
          'content'=> $model->content,
          'touser'=>$model->touser,
          'type'=>$model->type,
          'created_at'=>$model->created_at,
          'fromuser'=> $model->fromuser,
          'platform'=> 0
        ],
        'message'=>'接收聊天消息',
      ]));

      $model->save();
    	return $this->success();
    }
    return $this->fail('参数有误',$model);
  }


 
  /**
   * 分组发送
   */
  public function actionSendgroup()
  {
  	
  }

  /**
   * 对话消息
   */
  public function actionDialoguemsg()
  {
      $model = new Chat();
      if ($model->load(Yii::$app->request->post(),'') && $model->validate(['fromuser'])) {
        $fromuser = Yii::$app->request->post('fromuser');
        
        // Chat::updateAll(['status'=>1],['and','fromuser='.$fromuser,'touser='.$this->user->id]);
        $list = Chat::find()
        ->select('platform,type,fromuser,touser,content,platform,created_at')
        ->where(['or',['and','fromuser='.$this->user->id,'touser='.$fromuser],['and','fromuser='.$fromuser,'touser='.$this->user->id]])
        ->orderBy('created_at desc')
        ->offset(0)
        ->limit(10)
        // ->createCommand()
        // ->getRawSql();
        ->all();
      // return $this->fail($list);
        if ($list) {
          foreach ($list as &$v) {
            if ($v['type'] == 2) {
              $v['content'] = Url::base(true) . '/' . $v['content'];
            }
          }
        }
        return $this->success(array_reverse($list));
      }
      return $this->fail($model);
  }


  /**
   * 客服对话列表 只显示未读列表
   */
  public function actionDialoguelist(){
    $list = Chat::find()
      ->select('platform,type,fromuser,touser,content,created_at')
      ->where(['status'=>0,'touser'=>$this->user->id])
      ->groupBy('fromuser,touser')
      // ->createCommand()->getRawSql();
      ->asArray()
      ->all();
    return $this->success($list);
  }
}
