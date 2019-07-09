<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\mall\Goods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="goods-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'gn',
            'name',
            'brief',
            'price',
            'costprice',
            'mktprice',
            'image_id',
            'goods_cat_id',
            'goods_type_id',
            'brand_id',
            'is_nomal_virtual',
            'is_on_shelves',
            'on_shelves_time:datetime',
            'off_shelves_time:datetime',
            'stock',
            'freeze_stock',
            'volume',
            'weight',
            'weight_unit',
            'volume_unit',
            'content:ntext',
            'spes_desc:ntext',
            'params:ntext',
            'comments_count',
            'view_count',
            'buy_count',
            'sort',
            'is_recommend',
            'is_hot',
            'label_ids',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
