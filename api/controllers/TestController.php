<?php

namespace api\controllers;

use Yii;
use api\common\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestController implements the CRUD actions for Test model.
 */
class TestController extends BaseController
{

    public function actionIndex()
    {
        echo "string";
    }

}
