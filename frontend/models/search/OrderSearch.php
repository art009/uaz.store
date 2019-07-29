<?php

namespace frontend\models\search;

use common\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `backend\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'delivery_type', 'payment_type'], 'integer'],
            [['sum', 'delivery_sum'], 'number'],
            [['changed_at', 'created_at', 'updated_at'], 'safe'],
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
     * @param int $userId
     * @param array $params
     * @param int|array $notStatusFilter
     *
     * @return ActiveDataProvider
     */
    public function search($userId, $params, $notStatusFilter = null)
    {
        $query = Order::find();
        $query->where(['original_user_id' => $userId]);
        $query->orderBy('id DESC');

        if ($notStatusFilter) {
            $query->andWhere(['not in', 'status', $notStatusFilter]);
        }

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'sum' => $this->sum,
            'delivery_sum' => $this->delivery_sum,
            'delivery_type' => $this->delivery_type,
            'payment_type' => $this->payment_type,
            'changed_at' => $this->changed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
