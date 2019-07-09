<?php

namespace common\models\mall;

/**
 * This is the ActiveQuery class for [[GoodsImages]].
 *
 * @see GoodsImages
 */
class GoodsImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return GoodsImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GoodsImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
