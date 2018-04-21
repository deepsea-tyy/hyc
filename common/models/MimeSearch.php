<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mime;

/**
 * MimeSearch represents the model behind the search form of `common\models\Mime`.
 */
class MimeSearch extends Mime
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mime_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'file_total_count', 'file_total_size', 'status'], 'integer'],
            [['type_name', 'suffix'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Mime::find();

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
            'mime_id' => $this->mime_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'file_total_count' => $this->file_total_count,
            'file_total_size' => $this->file_total_size,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'type_name', $this->type_name])
            ->andFilterWhere(['like', 'suffix', $this->suffix]);

        return $dataProvider;
    }
}
