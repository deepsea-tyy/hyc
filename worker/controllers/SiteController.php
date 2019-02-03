<?php
namespace worker\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\BindForm;
use worker\models\PasswordResetRequestForm;
use worker\models\ResetPasswordForm;
use worker\models\SignupForm;
use GatewayClient\Gateway;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'only' => ['bind', 'login','loginout','signup','resetPassword'],
            // ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }

    public function actionIndex()
    {
        $data = [
          'type'=>'aa',
          'data'=>1,
          'msg'=>'测试发送',
        ];

        Gateway::$registerAddress = Yii::$app->params['workerConfig']['registerAddress'];
        Gateway::sendToUid(1, json_encode($data));

        return Yii::$app->params['workerConfig']['registerAddress'];
        return 'st';
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionBind()
    {
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值(ip不能是0.0.0.0)
        $model = new BindForm();
        if ($model->load(Yii::$app->request->post(),'')) {

            // 假设用户已经登录，用户uid和群组id在session中
            $uid      = Yii::$app->request->post('uid');
            $client_id = Yii::$app->request->post('sid');
            $group_id = 1;

            Gateway::$registerAddress = Yii::$app->params['workerConfig']['registerAddress'];
            // client_id与uid绑定
            Gateway::bindUid($client_id, $uid);
            // 加入某个群组（可调用多次加入多个群组）
            Gateway::joinGroup($client_id, $group_id);
            return ['status'=>1,'data'=>$model,'msg'=>'绑定成功'];
        }
        return ['status'=>0,'data'=>$model,'msg'=>'绑定失败'];
    }


    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(),'') && $model->login()) {
            return ['status'=>1,'data'=>['uid'=>Yii::$app->user->id],'msg'=>'登录成功'];
        } else {
            return ['status'=>0,'data'=>[],'msg'=>'账号或密码错误'];
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return ['status'=>1,'data'=>[],'msg'=>'成功退出'];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
