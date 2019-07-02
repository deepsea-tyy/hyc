<?php 
use yii\widgets\Breadcrumbs;
use common\helpers\Tools;
$links = Tools::getPageBreadcrumb();
?>
<div class="page-bar">
    <?=Breadcrumbs::widget([
        'options'=>['class' => 'page-breadcrumb'],
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => $links,
    ]); ?>
    <div class="page-toolbar" data-refresh="1">
        <div class="pull-right btn btn-sm">
            <i class="fa fa-refresh"></i>
        </div>
    </div>
</div>
<style type="text/css">
    .page-breadcrumb>li+li:before {
    content: "/ ";
    padding: 0 5px;
    color: #ccc;
}
</style>