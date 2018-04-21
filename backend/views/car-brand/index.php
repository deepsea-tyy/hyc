<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Car Brands';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-brand-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Car Brand', ['create'], ['class' => 'btn btn-success ajaxify']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'brand',
            'logo',
            'oid',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'template' => '{test} {view} {update} {delete}',
                'buttons' => [
                    'test' => function ($url, $model, $key) {
                      return  Html::a('<span class="icon-wallet"></span>', $url, ['title' => 'test','class'=>'ajaxify'] ) ;
                     },
                     'view' => function ($url, $model, $key) {
                      return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看','class'=>'ajaxify'] ) ;
                     },
                     'update' => function ($url, $model, $key) {
                      return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '更新','class'=>'ajaxify'] ) ;
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
                ],

            ],
        ],
    ]); ?>
</div>
