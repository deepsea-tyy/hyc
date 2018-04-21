<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LinePrice */

$this->title = 'Update Line Price: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Line Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="line-price-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
