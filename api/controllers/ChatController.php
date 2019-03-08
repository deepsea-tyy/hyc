<?php

namespace api\controllers;

use Yii;
use GatewayClient\Gateway;
use common\models\ChatMessage;
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
      $model = new ChatMessage();
      if ($model->load(Yii::$app->request->post(),'') && $model->validate()) {

        if ($model->type == 2) {
          $path = 'uploads/image/message';

          $patch = $path . '/' . date('Ymd');
          $imgPath = '';

          if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $model->message, $result)){
            $type = $result[2];
            $imgPath = $path.'/'.date('Ymd',time()).'/';

            if(!file_exists($imgPath)){
                mkdir($imgPath, 0777, true);
            }
            $imgPath = $imgPath.time().'.'.$type;

            if (file_put_contents($imgPath, base64_decode(str_replace($result[1], '', $model->message)))){
                $model->message = $imgPath;
            }
          }
          if (!$imgPath) return $this->fail();
        }
        
        $msg = $model->type == 2 ? Url::base(true) . '/' . $model->message : $model->message;

        Gateway::sendToUid($model->touid, json_encode([
          'type'=>'receiveMsg',
          'data'=>['msg'=> $msg,'fromuid'=>$model->fromuid,'type'=>$model->type],
          'msg'=>'接收消息',
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
  public function actionDialoguelist()
  {
      $model = new ChatMessage();
      if ($model->load(Yii::$app->request->post(),'') && $model->validate(['fromuid','touid','activeuid'])) {
        $fromuid = Yii::$app->request->post('fromuid');
        $touid = Yii::$app->request->post('touid');
        $activeuid = Yii::$app->request->post('activeuid');
        
        ChatMessage::updateAll(['status'=>1],['and','fromuid='.$fromuid,'touid='.$touid]);
        $list = ChatMessage::find()
        ->where(['or',['and','fromuid='.$fromuid,'touid='.$touid],['and','fromuid='.$touid,'touid='.$fromuid]])
        ->orderBy('created_at asc')
        // ->createCommand()
        // ->getRawSql()
        // ->offset(10)
        // ->limit(10)
        ->all();
        if ($list) {
          foreach ($list as &$v) {
            if ($v['type'] == 2) {
              $v['message'] = Url::base(true) . '/' . $v['message'];
            }
          }
        }
        return $this->success($list);
      }
      return $this->fail();
  }

}
