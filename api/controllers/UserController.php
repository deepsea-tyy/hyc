<?php
namespace api\controllers;

use Yii;
use common\models\LoginForm;

/**
 * 用户
 */
class UserController extends Api
{
    public function allowAction()
    {
        return [
            'login','logout','info'
        ];
    }

    public function actionInfo()
    {
        $info = [
        	'roles'=>['admin'],
    	    'introduction'=>'I am a super administrator',
    	    'avatar'=>'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
    	    'name'=>'Super Admin'
    	];
        return $this->success($info);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->success();
    }

    /**
     * 登录获取tonken
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        if ($model->load(Yii::$app->request->post(),'') &&  $user = $model->apiLogin()) {
            return $this->success(['token'=>$user->access_token]);
        }
        return $this->fail($model->errors);
    }
}
