<?php

namespace common\models\applet;

/**
 * This is the ActiveQuery class for [[WeixinAppletMessage]].
 *
 * @see WeixinAppletMessage
 */
class WeixinAppletMessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return WeixinAppletMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return WeixinAppletMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
