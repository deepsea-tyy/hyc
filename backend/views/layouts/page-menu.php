<?php
use mdm\admin\components\MenuHelper;
use backend\widgets\Menu;

$menu =  MenuHelper::getAssignedMenu(\Yii::$app->user->id,null,function ($menu)
{
    $data = json_decode($menu['data'],true);

    $item = [
                'label' => $data['title'],
                // 'label' => $menu['data'],
                'url' => MenuHelper::parseRoute($menu['route']),
            ];
    if ($menu['children'] != []) {
        $item['items'] = $menu['children'];
        $item['url'] = '#';
    }

    $item['icon'] = $data['icon'];
    // $item['itemOptions'] = [
    //         'class'=>'active'
    //     ];
    $item['arrow'] = $menu['children'] ? 'arrow' : '';

    return $item;
});
$item = [
            [
                'options' => ['class'=>'sidebar-search-wrapper'],
                'template' => '<form class="sidebar-search sidebar-search-bordered" action="" method="POST">
                                    <a href="javascript:;" class="remove">
                                        <i class="icon-close"></i>
                                    </a>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search...">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" class="btn submit">
                                                <i class="icon-magnifier"></i>
                                            </a>
                                        </span>
                                    </div>
                                </form>',
            ],
        ];
        
$menu = array_merge($item,$menu);

?>
  
<div class="page-sidebar navbar-collapse collapse">
    <?=Menu::widget([
        'submenuTemplate'=>"\n<ul class=\"sub-menu\">\n{items}\n</ul>\n",
        'options'=>[
                'id'=>'menu',
                'class'=>'page-sidebar-menu  page-header-fixed',
                'data-keep-expanded'=>'false',
                'data-auto-scroll'=>'true',
                'data-slide-speed'=>'200',
                'style'=>'padding-top: 20px'
            ],
        'itemOptions'=>['class'=>'nav-item'],
        //ajaxify ajax请求方法. nav-toggle 跳转
        'linkTemplate'=>'<a href="{url}" class="nav-toggle nav-link ajaxify">
        <i class="{icon}"></i>
        <span class="title">{label}</span>
        <span class="selected"></span>
        <span class="{arrow}"></span>
        </a>',
        'items'=>$menu,
    ]);?>

</div>
