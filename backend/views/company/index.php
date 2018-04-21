<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success ajaxify']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'c_id',
            'codeid',
            'code_city',
            'pid',
            'c_name',
            //'c_website',
            //'c_logo',
            //'c_add',
            //'c_gps',
            //'c_area',
            //'reg_ip',
            //'reg_time:datetime',
            //'c_level',
            //'review',
            //'review_user',
            //'review_log_id',
            //'review_time:datetime',
            //'zdjj_id',
            //'zdjj_ks',
            //'zdjj_js',
            //'zdjj_mod',
            //'invoice_name',
            //'invoice_taxcode',
            //'invoice_add',
            //'invoice_tel',
            //'invoice_bank',
            //'invoice_bank_account',
            //'cj',
            //'fw',
            //'hp',
            //'cp',
            //'xl',
            //'xlr',
            //'xldh',
            //'remark',
            //'is_bidding',
            //'bidding',
            //'bidding_num',
            //'bidding_back',
            //'bidding_modify',
            //'created_at',
            //'updated_at',
            //'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
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
    <?php Pjax::end(); ?>
</div>
