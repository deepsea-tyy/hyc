<?php

namespace common\models\mall;

use Yii;
use common\models\File;

/**
 * This is the model class for table "mall_goods_images".
 *
 * @property int $goods_id 商品ID
 * @property string $image_id 图片ID
 * @property int $sort 图片排序
 */
class GoodsImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mall_goods_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'image_id'], 'required'],
            [['goods_id', 'sort'], 'integer'],
            [['image_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'image_id' => '图片ID',
            'sort' => '图片排序',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GoodsImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GoodsImagesQuery(get_called_class());
    }

    public function getImage()
    {
        return $this->hasOne(File::className(), ['id' => 'image_id']);
    }
}
