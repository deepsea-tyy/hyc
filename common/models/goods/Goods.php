<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "shop_goods".
 *
 * @property int $id 商品ID
 * @property string $gn 商品编码
 * @property string $name 商品名称
 * @property string $brief 商品简介
 * @property string $price 商品价格
 * @property string $costprice 成本价
 * @property string $mktprice 市场价
 * @property string $image_id 默认图片 图片id
 * @property int $goods_cat_id 商品分类ID 关联category.id
 * @property int $goods_type_id 商品类别ID 关联goods_type.id
 * @property int $brand_id 品牌ID 关联brand.id
 * @property int $is_nomal_virtual 虚拟正常商品 1=正常 2=虚拟
 * @property int $is_on_shelves 是否上架 1=上架 2=下架
 * @property int $on_shelves_time 上架时间
 * @property int $off_shelves_time 下架时间
 * @property int $stock 库存
 * @property int $freeze_stock 冻结库存
 * @property string $volume 体积
 * @property string $weight 重量
 * @property string $weight_unit 重量单位
 * @property string $volume_unit 体积单位
 * @property string $content 商品详情
 * @property string $spes_desc 商品规格序列号存储
 * @property string $params 参数序列化
 * @property int $comments_count 评论总数
 * @property int $view_count 浏览总数
 * @property int $buy_count 购买总数
 * @property int $sort 商品排序 越小越靠前
 * @property int $is_recommend 是否推荐，1是，2不是推荐
 * @property int $is_hot 是否热门，1是，2否
 * @property string $label_ids 标签id逗号分隔
 * @property int $status 1正常2删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'costprice', 'mktprice', 'volume', 'weight'], 'number'],
            [['goods_cat_id', 'goods_type_id', 'brand_id', 'is_nomal_virtual', 'is_on_shelves', 'on_shelves_time', 'off_shelves_time', 'stock', 'freeze_stock', 'comments_count', 'view_count', 'buy_count', 'sort', 'is_recommend', 'is_hot', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content', 'spes_desc', 'params'], 'string'],
            [['gn'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 200],
            [['brief'], 'string', 'max' => 255],
            [['image_id'], 'string', 'max' => 32],
            [['weight_unit', 'volume_unit'], 'string', 'max' => 20],
            [['label_ids'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品ID',
            'gn' => '商品编码',
            'name' => '商品名称',
            'brief' => '商品简介',
            'price' => '商品价格',
            'costprice' => '成本价',
            'mktprice' => '市场价',
            'image_id' => '默认图片 图片id',
            'goods_cat_id' => '商品分类ID 关联category.id',
            'goods_type_id' => '商品类别ID 关联goods_type.id',
            'brand_id' => '品牌ID 关联brand.id',
            'is_nomal_virtual' => '虚拟正常商品 1=正常 2=虚拟',
            'is_on_shelves' => '是否上架 1=上架 2=下架',
            'on_shelves_time' => '上架时间',
            'off_shelves_time' => '下架时间',
            'stock' => '库存',
            'freeze_stock' => '冻结库存',
            'volume' => '体积',
            'weight' => '重量',
            'weight_unit' => '重量单位',
            'volume_unit' => '体积单位',
            'content' => '商品详情',
            'spes_desc' => '商品规格序列号存储',
            'params' => '参数序列化',
            'comments_count' => '评论总数',
            'view_count' => '浏览总数',
            'buy_count' => '购买总数',
            'sort' => '商品排序 越小越靠前',
            'is_recommend' => '是否推荐，1是，2不是推荐',
            'is_hot' => '是否热门，1是，2否',
            'label_ids' => '标签id逗号分隔',
            'status' => '1正常2删除',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GooQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GooQuery(get_called_class());
    }
}
