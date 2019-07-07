<?php

namespace api\modules\rbac\controllers;

use Yii;
use mdm\admin\models\AuthItem;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use mdm\admin\components\Configs;
use mdm\admin\components\Helper;

class ItemController extends \api\controllers\Api
{
    public function allowAction()
    {
        return [
            'index','assign','view','update','remove','create','delete'
        ];
    }
	/**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new AuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $menus = [];
        $i=0;
        foreach ($dataProvider->getModels() as $k => $v) {
            $menus[$i]['description'] = $v->description;
            $menus[$i]['name'] = $k;
            $menus[$i]['rule_name'] = $v->ruleName;
            $menus[$i]['data'] = $v->data;
            // $menus[$i]['routes'] = MenuHelper::getAssignedMenuByRole($k, null, function ($menu)
            // {
            //     $data = json_decode($menu['data'],true);
            //     $children = array_filter($menu['children']);
            //     if ($children) $item['children'] = $children;

            //     $item['path'] = $menu['route'] ?? '';
            //     if (!empty($data['component']))     $item['component'] = $data['component'];

            //     if (!empty($data['hidden']))        $item['hidden'] = $data['hidden'];
            //     if (!empty($data['alwaysShow']))    $item['alwaysShow'] = $data['alwaysShow'];
            //     if (!empty($data['redirect']))      $item['redirect'] = $data['redirect'];
            //     if (!empty($data['meta']))          $item['meta'] = $data['meta'];
            //     return $item;
            // });
            $i++;
        }
        return $this->success($menus);
    }

    /**
     * Displays a single AuthItem model.
     * @param  string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $data = $this->format($model->getItems());
        return $model ? $this->success($data) : $this->fail('角色不存在');
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null);
        $model->type = $this->type;
        Yii::$app->i18n->translations['*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@mdm/admin/messages',
            'fileMap' => [
                'rbac-admin' => 'rbac-admin.php',
            ],
        ];
        if ($model->load(Yii::$app->getRequest()->post(),'') && $model->save()) {
            return $this->success();
        } else {
            return $this->fail($model->getErrors(),Yii::t('rbac-admin', 'Name'));
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post(),'') && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Configs::authManager()->remove($model->item);
        Helper::invalidate();

        return $this->redirect(['index']);
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->addChildren($items);
        $data = $this->format($model->getItems());
        return $model && $success ? $this->success($data) : $this->fail('授权失败，请重试');
    }

    /**
     * Assign or remove items
     * @param string $id
     * @return array
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->removeChildren($items);
        $data = $this->format($model->getItems());
        return $model && $success ? $this->success($data) : $this->fail('授权失败，请重试');
    }

    public function format($items)
    {
        $res = [];
        foreach ($items['available'] as $k => $v) {
            $res['available'][$v][] = $k;
        }
        foreach ($items['assigned'] as $k => $v) {
            $res['assigned'][$v][] = $k;
        }
        return $res;
    }

    /**
     * Type of Auth Item.
     * @return integer
     */
    public function getType()
    {
        
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $auth = Configs::authManager();
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
