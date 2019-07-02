<?php

namespace backend\controllers;

use Yii;
use common\models\mall\Goods;
use common\models\mall\GoodsSearch;
use backend\controllers\Base;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends Base
{
    /**
     * {@inheritdoc}
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
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods();

        if ($model->addGoods(Yii::$app->request->post())) {
            return $this->success();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->editGoods(Yii::$app->request->post())) {
            return $this->success();
        }

        $goods_images = $model->getImages()->orderBy('sort')->all();

        $initialPreviewConfig = array_map(function ($v)
        {
            global $image_ids;
            $file = $v->getImage()->select(['file_url','name'])->one();
            $image_ids[] = ['image_id'=>$v->image_id];
            $item['url'] = Url::to([\common\models\File::$deleteUrl,'edit'=>1]);
            $item['type'] = 'image';
            $item['key'] = $v->image_id;
            $item['caption'] = $file->name;
            $item['file_url'] = $file->file_url;
            return $item;
        }, $goods_images);

        global $image_ids;
        return $this->render('update', [
            'model' => $model,
            'initialPreview' => array_column($initialPreviewConfig, 'file_url'),
            'initialPreviewConfig' => $initialPreviewConfig,
            'image_ids' => json_encode($image_ids),
        ]);
    }

    /**
     * Deletes an existing Goods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        return $this->findModel($id)->delete() ? $this->success() : $this->fail();
    }

    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
