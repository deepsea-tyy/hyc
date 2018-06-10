<?php

namespace backend\controllers;

use common\models\car\CarBrand;
use common\models\Area;

class PublicController extends \backend\controllers\CommonController
{
    // 测试接口
    public function actionIndex()
    {
    	$list = CarBrand::find()->select(['id','text'=>'brand'])->asArray()->all();
    	
        $this->asJson(['total_count'=>count($list),'items'=>$list]);
    }

    /*
     * 城市3级联动
     */
    public function actionArea()
    {
    	$parents = $arr = [];
    	$list = Area::find()->select(['id'=>'codeid','name'=>'city','pid'=>'parentid'])->asArray()->all();    	
    	$this->asJson($this->list_to_tree($list));
    }

    /** 
     * 将返回的数据集转换成树 
     * @param  array   $list  数据集 
     * @param  string  $pk    主键 
     * @param  string  $pid   父节点名称 
     * @param  string  $child 子节点名称 
     * @param  integer $root  根节点ID 
     * @return array          转换后的树 
     */  
    public function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root=0) {
        $tree = array();// 创建Tree
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid]; 
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

}
