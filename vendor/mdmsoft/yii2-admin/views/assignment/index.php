<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'backend\common\grid\ActionColumn',
    'template' => '{view}'
];
?>
<div class="assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=>'Header Before 1', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
                ],
                'options'=>['class'=>'skip-export'] // remove this row from export
            ]
        ],
        'toolbar' =>  [
            ['content'=>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>'Add Book', 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) 
            ],
            '{export}',
            '{toggleData}'
        ],
        'pjax' => true,
        'pjaxSettings'=>['options'=>['enablePushState'=>false,'enableReplaceState'=>false]],
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 100],
        'showPageSummary' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]);
    ?>

</div>
