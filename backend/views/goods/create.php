<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\mall\Goods */

$this->title = 'Create Goods';
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('/layouts/page-container-breadcrumbs');?>
<div class="goods-create"><br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
