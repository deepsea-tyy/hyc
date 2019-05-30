<?php

namespace backend\controllers;

use Yii;
use common\models\SystemConfig;
use yii\data\ActiveDataProvider;
use backend\controllers\Base;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;

/**
 * SystemConfigController implements the CRUD actions for SystemConfig model.
 */
class SystemConfigController extends Base
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
     * Lists all SystemConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SystemConfig::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemConfig model.
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
     * Creates a new SystemConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemConfig();

        $dynamicModel = new DynamicModel([
            'f_id'
        ]);
 
        // $dynamicModel->attachBehavior('upload', [
        //     'class' => 'mdm\upload\UploadBehavior',
        //     'attribute' => 'file',
        //     'savedAttribute' => 'f_id' // coresponding with $dynamicModel->f_id
        // ]);
     
        // // rule untuk dynamicModel
        // $dynamicModel->addRule('file','required')->addRule('file', 'file', ['extensions' => ['jpg','png']]);

        // if ($dynamicModel->load(Yii::$app->request->post()) && $dynamicModel->validate()) {
        //     if ($dynamicModel->saveUploadedFile() !== false) {

        //         echo $dynamicModel->f_id;exit;
                
        //         // Yii::$app->session->setFlash('success', 'Upload Sukses');
        //     }
        // }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->actionView($model->id);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dynamicModel' => $dynamicModel,
            ]);
        }
    }

    /**
     * Updates an existing SystemConfig model.
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
     * Deletes an existing SystemConfig model.
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
     * Finds the SystemConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
