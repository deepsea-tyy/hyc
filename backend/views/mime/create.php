<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Mime */

$this->title = 'Create Mime';
$this->params['breadcrumbs'][] = ['label' => 'Mimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mime-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
