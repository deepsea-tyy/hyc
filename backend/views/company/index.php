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
    <?php //Pjax::begin(); ?>
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

            ['class' => 'backend\common\grid\ActionColumn']
        ],
    ]); ?>
    <?php //Pjax::end(); ?>
</div>
