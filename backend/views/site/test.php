<?php
use yii\bootstrap\Alert;
echo Alert::widget([
  'options' => [
      'class' => 'alert-info',
  ],
  'body' => '保存失败！',
]);