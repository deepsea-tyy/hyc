<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Mime */

$this->title = $model->mime_id;
$this->params['breadcrumbs'][] = ['label' => 'Mimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mime-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mime_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mime_id], [
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
            'mime_id',
            'type_name',
            'suffix',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'file_total_count',
            'file_total_size',
            'status',
        ],
    ]) ?>

</div>
