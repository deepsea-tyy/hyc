<?php

namespace common\models\hyc;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\hyc\Company;

/**
 * CompanySearch represents the model behind the search form of `common\models\hyc\Company`.
 */
class CompanySearch extends Company
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'codeid', 'pid', 'reg_time', 'c_level', 'review', 'review_user', 'review_log_id', 'review_time', 'zdjj_id', 'zdjj_ks', 'zdjj_js', 'zdjj_mod', 'cj', 'fw', 'hp', 'cp', 'xl', 'is_bidding', 'bidding', 'bidding_num', 'bidding_back', 'bidding_modify', 'created_at', 'updated_at', 'status'], 'integer'],
            [['code_city', 'c_name', 'c_website', 'c_logo', 'c_add', 'c_gps', 'c_area', 'reg_ip', 'invoice_name', 'invoice_taxcode', 'invoice_add', 'invoice_tel', 'invoice_bank', 'invoice_bank_account', 'xlr', 'xldh', 'remark'], 'safe'],
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
        $query = Company::find();

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
            'c_id' => $this->c_id,
            'codeid' => $this->codeid,
            'pid' => $this->pid,
            'reg_time' => $this->reg_time,
            'c_level' => $this->c_level,
            'review' => $this->review,
            'review_user' => $this->review_user,
            'review_log_id' => $this->review_log_id,
            'review_time' => $this->review_time,
            'zdjj_id' => $this->zdjj_id,
            'zdjj_ks' => $this->zdjj_ks,
            'zdjj_js' => $this->zdjj_js,
            'zdjj_mod' => $this->zdjj_mod,
            'cj' => $this->cj,
            'fw' => $this->fw,
            'hp' => $this->hp,
            'cp' => $this->cp,
            'xl' => $this->xl,
            'is_bidding' => $this->is_bidding,
            'bidding' => $this->bidding,
            'bidding_num' => $this->bidding_num,
            'bidding_back' => $this->bidding_back,
            'bidding_modify' => $this->bidding_modify,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'code_city', $this->code_city])
            ->andFilterWhere(['like', 'c_name', $this->c_name])
            ->andFilterWhere(['like', 'c_website', $this->c_website])
            ->andFilterWhere(['like', 'c_logo', $this->c_logo])
            ->andFilterWhere(['like', 'c_add', $this->c_add])
            ->andFilterWhere(['like', 'c_gps', $this->c_gps])
            ->andFilterWhere(['like', 'c_area', $this->c_area])
            ->andFilterWhere(['like', 'reg_ip', $this->reg_ip])
            ->andFilterWhere(['like', 'invoice_name', $this->invoice_name])
            ->andFilterWhere(['like', 'invoice_taxcode', $this->invoice_taxcode])
            ->andFilterWhere(['like', 'invoice_add', $this->invoice_add])
            ->andFilterWhere(['like', 'invoice_tel', $this->invoice_tel])
            ->andFilterWhere(['like', 'invoice_bank', $this->invoice_bank])
            ->andFilterWhere(['like', 'invoice_bank_account', $this->invoice_bank_account])
            ->andFilterWhere(['like', 'xlr', $this->xlr])
            ->andFilterWhere(['like', 'xldh', $this->xldh])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
