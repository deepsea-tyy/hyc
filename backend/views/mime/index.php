<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\MimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mimes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mime-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mime', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'mime_id',
            'type_name',
            'suffix',
            'created_by',
            'updated_by',
            //'created_at',
            //'updated_at',
            //'file_total_count',
            //'file_total_size',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'pjax' => true,
        'pjaxSettings'=>['options'=>['enablePushState'=>false,'enableReplaceState'=>false]],
    ]); ?>
</div>
