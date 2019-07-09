<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\mall\Goods */

$this->title = 'Update Goods: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?=$this->render('/layouts/page-container-breadcrumbs');?>
<div class="goods-update"><br>

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig,
        'image_ids' => $image_ids,
    ]) ?>

</div>
