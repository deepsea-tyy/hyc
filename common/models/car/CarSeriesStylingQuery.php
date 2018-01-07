<?php

namespace common\models\car;

/**
 * This is the ActiveQuery class for [[CarSeriesStyling]].
 *
 * @see CarSeriesStyling
 */
class CarSeriesStylingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CarSeriesStyling[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CarSeriesStyling|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
