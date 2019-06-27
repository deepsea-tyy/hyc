<?php

namespace backend\controllers;

use Yii;
use backend\controllers\Base;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\jobs\Test;
use backend\models\Messages;

/**
 * IndexController implements the CRUD actions for Mime model.
 */
class IndexController extends Base
{
    public function allowAction()
    {
        return ['index'];
    }

    public function actionIndex()
    {
        $row = Yii::$app->queue2->redis->hgetall("ms.messages");
        echo "<pre>";
        print_r($row);exit();
        return $this->asJson($row);
        // $id = Yii::$app->queue2->push(new Test([
        //     'p1' => 'p1',
        //     'p2' => 'p2',
        // ]));
    }

}
