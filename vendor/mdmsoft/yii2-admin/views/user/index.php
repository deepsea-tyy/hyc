<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'email:email',
            'created_at:date',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 0 ? 'Inactive' : 'Active';
                },
                'filter' => [
                    0 => 'Inactive',
                    10 => 'Active'
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Helper::filterActionColumn(['view', 'activate', 'delete']),
                'buttons' => [

                     'view' => function ($url, $model, $key) {
                      return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看','class'=>'ajaxify'] ) ;
                     },
                    'activate' => function($url, $model) {
                        if ($model->status == 10) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('rbac-admin', 'Activate'),
                            'aria-label' => Yii::t('rbac-admin', 'Activate'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    },
                     'delete' => function ($url, $model, $key) {
                      return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, 
                        [
                            'title' => '删除',
                            'class'=>'ajaxify',
                            'method'=>'POST',
                            'data-sa'=>'1',
                            'data-title'=>'删除',
                            'data-message'=>'确定删除?',
                            'data-type'=>'info',
                            'data-allow-outside-click'=>'false',
                            'data-show-confirm-button'=>'true',
                            'data-show-cancel-button'=>'true',
                            'data-confirm-button-class'=>'btn-danger',
                            'data-cancel-button-class'=>'btn-default',
                            'data-close-on-confirm'=>'false',
                            'data-close-on-cancel'=>'true',
                            'data-confirm-button-text'=>'确定',
                            'data-cancel-button-text'=>'取消',
                        ] ) ;
                     }
                    ]
                ],
            ],
        ]);
        ?>
</div>
