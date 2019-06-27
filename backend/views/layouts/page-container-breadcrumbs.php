<?php 
use yii\widgets\Breadcrumbs;

?>
<div class="page-bar">
    <?=Breadcrumbs::widget([
        'options'=>['class' => 'page-breadcrumb'],
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => [
            [
                'label' => 'Post Category',
                'url' => ['/', 'id' => 10],
                'template' => "<li><b>{link}</b></li>\n", // template for this link only
            ],
            ['label' => 'Sample Post', 'url' => ['/', 'id' => 1]],
            'Edit',
        ],
    ]); ?>
    <div class="page-toolbar">
        <div id="dashboard-report-range" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
            <i class="icon-calendar"></i>&nbsp;
            <span class="thin uppercase hidden-xs"></span>&nbsp;
            <i class="fa fa-angle-down"></i>&nbsp;
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