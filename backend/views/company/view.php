<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = $model->c_id;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->c_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->c_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'c_id',
            'codeid',
            'code_city',
            'pid',
            'c_name',
            'c_website',
            'c_logo',
            'c_add',
            'c_gps',
            'c_area',
            'reg_ip',
            'reg_time:datetime',
            'c_level',
            'review',
            'review_user',
            'review_log_id',
            'review_time:datetime',
            'zdjj_id',
            'zdjj_ks',
            'zdjj_js',
            'zdjj_mod',
            'invoice_name',
            'invoice_taxcode',
            'invoice_add',
            'invoice_tel',
            'invoice_bank',
            'invoice_bank_account',
            'cj',
            'fw',
            'hp',
            'cp',
            'xl',
            'xlr',
            'xldh',
            'remark',
            'is_bidding',
            'bidding',
            'bidding_num',
            'bidding_back',
            'bidding_modify',
            'created_at',
            'updated_at',
            'status',
        ],
    ]) ?>

</div>
