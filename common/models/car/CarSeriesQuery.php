<?php

namespace common\models\car;

/**
 * This is the ActiveQuery class for [[CarSeries]].
 *
 * @see CarSeries
 */
class CarSeriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CarSeries[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CarSeries|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
