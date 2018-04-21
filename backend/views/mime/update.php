<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Mime */

$this->title = 'Update Mime: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Mimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mime_id, 'url' => ['view', 'id' => $model->mime_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mime-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
