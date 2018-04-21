<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LinePrice */

$this->title = 'Create Line Price';
$this->params['breadcrumbs'][] = ['label' => 'Line Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="line-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
