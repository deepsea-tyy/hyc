<?php
namespace api\controllers;

use common\controllers\BaseController;
use api\common\behaviors\CompositeAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;


/**
 * 接口基类
 */
class Api extends BaseController
{
    public $layout = false;

    public $user;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
            	[
            		'class'  => HttpHeaderAuth::className(),
            		'header' => 'X-Token'
            	],
            	[
            		'class'  => QueryParamAuth::className(),
            		'tokenParam' => 'token'
            	],
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function success($data=[],$message='success')
    {
        return ['code'=>200,'data'=>$data,'message'=>$message];
    }


    public function fail($message='fail',$data=[])
    {
        return ['code'=>0,'data'=>$data,'message'=>$message];
    }
}
