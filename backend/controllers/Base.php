<?php

namespace backend\controllers;

use Yii;

class Base extends \yii\web\Controller
{
	 /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // 'islogin' => [
            //     'class' => \backend\common\behaviors\IsLogin::className(),
            //     'actions' => '*',
            // ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function render($view, $params = [])
    {
        if (Yii::$app->request->getIsPjax() || Yii::$app->request->getIsAjax()) {
            return $this->renderAjax($view, $params, $this);
        }else{
            return parent::render($view, $params);
        }
    }

    public function success($data=[],$msg='操作成功')
    {
        return $this->asJson(['status'=>200,'data'=>$data,'msg'=>$msg]);
    }

    public function fail($msg='操作失败',$data=[])
    {
        return $this->asJson(['status'=>400,'data'=>$data,'msg'=>$msg]);
    }
}
