<?php

namespace backend\controllers;

use Yii;
use common\models\LinePrice;
use yii\data\ActiveDataProvider;
use backend\controllers\CommonController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\queue\DownloadJob;

/**
 * LinePriceController implements the CRUD actions for LinePrice model.
 */
class LinePriceController extends CommonController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LinePrice models.
     * @return mixed
     */
    public function actionIndex()
    {
        echo Yii::getAlias('@bower');

echo "<br/>for test";
//         $info = Yii::$app->queue->info;

//        // $id =  Yii::$app->queue->push(new DownloadJob([
//        //      'url' => 'https://car3.autoimg.cn/cardfs/product/g18/M0C/C4/C7/1024x0_1_q87_autohomecar__wKgH2Vn5qFaAGtp5AAiHxHJl-w4315.jpg',
//        //      'file' => './image.jpg',
//        //  ]));
// $id = 1;
//         Yii::$app->queue->reserve();
//        // The job is waiting for execute.
//        $isWaiting = Yii::$app->queue->isWaiting($id);

//         // Worker gets the job from queue, and executing it.
//        $isReserved = Yii::$app->queue->isReserved($id);

//         // Worker has executed the job.
//        $isDone = Yii::$app->queue->isDone($id);
//         // Yii::$app->queue->run(false);
// echo "ok".time();
//        var_dump($id,$isWaiting,$isReserved,$isDone);

        // Yii::$app->queue->delay(5)->push(new DownloadJob([
        //     'url' => 'http://example.com/image.jpg',
        //     'file' => '/tmp/image.jpg',
        // ]));
        /*$dataProvider = new ActiveDataProvider([
            'query' => LinePrice::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single LinePrice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LinePrice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LinePrice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LinePrice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LinePrice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LinePrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LinePrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LinePrice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
