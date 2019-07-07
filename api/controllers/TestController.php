<?php

namespace api\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestController implements the CRUD actions for Test model.
 */
class TestController extends Api
{

    public function actionIndex()
    {

    	return $this->success(Yii::$app->user->identity);
    }

}
