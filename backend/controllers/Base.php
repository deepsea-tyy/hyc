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

}
