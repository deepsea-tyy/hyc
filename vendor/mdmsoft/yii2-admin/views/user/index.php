<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('rbac-admin', 'Create User'), ['create'], ['class' => 'btn btn-success ajaxify']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
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
                'class' => 'backend\common\grid\ActionColumn',
                'template' => Helper::filterActionColumn(['view', 'update', 'activate', 'delete']),
                'buttons' => [
                    'activate' => function($url, $model) {
                        if ($model->status == 1) {
                            return '';
                        }
                        $options = [
                            'title' => '启用',
                            'class'=>'ajax-request',
                            'data-title'=>'启用',
                            'data-message'=>'确定启用?',
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
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    }
                    ]
                ],
            ],
            'pjax' => true,
            'pjaxSettings'=>['options'=>['enablePushState'=>false,'enableReplaceState'=>false]],

        ]);
        ?>
</div>
