<?php

namespace common\models\goods;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\goods\Goods;

/**
 * GoodsQuery represents the model behind the search form of `common\models\goods\Goods`.
 */
class GoodsQuery extends Goods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'goods_cat_id', 'goods_type_id', 'brand_id', 'is_nomal_virtual', 'is_on_shelves', 'on_shelves_time', 'off_shelves_time', 'stock', 'freeze_stock', 'comments_count', 'view_count', 'buy_count', 'sort', 'is_recommend', 'is_hot', 'status', 'created_at', 'updated_at'], 'integer'],
            [['gn', 'name', 'brief', 'image_id', 'weight_unit', 'volume_unit', 'content', 'spes_desc', 'params', 'label_ids'], 'safe'],
            [['price', 'costprice', 'mktprice', 'volume', 'weight'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Goods::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'costprice' => $this->costprice,
            'mktprice' => $this->mktprice,
            'goods_cat_id' => $this->goods_cat_id,
            'goods_type_id' => $this->goods_type_id,
            'brand_id' => $this->brand_id,
            'is_nomal_virtual' => $this->is_nomal_virtual,
            'is_on_shelves' => $this->is_on_shelves,
            'on_shelves_time' => $this->on_shelves_time,
            'off_shelves_time' => $this->off_shelves_time,
            'stock' => $this->stock,
            'freeze_stock' => $this->freeze_stock,
            'volume' => $this->volume,
            'weight' => $this->weight,
            'comments_count' => $this->comments_count,
            'view_count' => $this->view_count,
            'buy_count' => $this->buy_count,
            'sort' => $this->sort,
            'is_recommend' => $this->is_recommend,
            'is_hot' => $this->is_hot,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gn', $this->gn])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'brief', $this->brief])
            ->andFilterWhere(['like', 'image_id', $this->image_id])
            ->andFilterWhere(['like', 'weight_unit', $this->weight_unit])
            ->andFilterWhere(['like', 'volume_unit', $this->volume_unit])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'spes_desc', $this->spes_desc])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'label_ids', $this->label_ids]);

        return $dataProvider;
    }
}
