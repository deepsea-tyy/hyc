<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\mall\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goods';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('/layouts/page-container-breadcrumbs');?>
<div class="goods-index">

    <p>
        <?= Html::a('Create Goods', ['create'], ['class' => 'btn btn-success','data-pjax'=>1]) ?>
    </p>

    <?php Pjax::begin(['clientOptions'=>['container'=>'#container']]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            // 'gn',
            'name',
            // 'brief',
            'price',
            //'costprice',
            //'mktprice',
            //'image_id',
            //'goods_cat_id',
            //'goods_type_id',
            //'brand_id',
            //'is_nomal_virtual',
            //'is_on_shelves',
            //'on_shelves_time:datetime',
            //'off_shelves_time:datetime',
            //'stock',
            //'freeze_stock',
            //'volume',
            //'weight',
            //'weight_unit',
            //'volume_unit',
            //'content:ntext',
            //'spes_desc:ntext',
            //'params:ntext',
            //'comments_count',
            //'view_count',
            //'buy_count',
            //'sort',
            //'is_recommend',
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'is_hot', 
            ],
            'label_ids',
            //'status',
            //'created_at',
            [
                'class'=>'kartik\grid\DataColumn',
                'attribute'=>'updated_at', 
                'format'=>['date', 'php:Y-m-d'],
            ],

            [
                'class' => 'backend\common\grid\ActionColumn',
                'buttonOptions' => [
                    'data-goUrl' => '/goods'
                ]
            ],
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>
