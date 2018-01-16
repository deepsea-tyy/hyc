<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SystemConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'System Configs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create System Config', ['create'], ['class' => 'btn btn-success ajaxify']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'content:ntext',
            'introduction',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
