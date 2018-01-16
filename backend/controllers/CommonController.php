<?php

namespace backend\controllers;

class CommonController extends \yii\web\Controller
{
    // public $layout = false;
	 /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'islogin' => [
                'class' => \backend\common\behaviors\IsLogin::className(),
                'actions' => '*',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function render($view, $params = [])
    {
        return $this->renderAjax($view, $params, $this);
    }

    public function actionTest()
    {
        return $this->renderAjax('/site/test');
    }

}
