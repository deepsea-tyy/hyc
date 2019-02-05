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

}
