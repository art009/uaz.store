<?php

namespace app\modules\pms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pms\models\ProviderItem;

/**
 * ProviderItemSearch represents the model behind the search form about `app\modules\pms\models\ProviderItem`.
 */
class ProviderItemSearch extends ProviderItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'provider_id', 'rest', 'ignored'], 'integer'],
            [['code', 'vendor_code', 'title', 'unit', 'manufacturer', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        $query = ProviderItem::find();

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
            'provider_id' => (isset ($_GET['providerId'])? $_GET['providerId'] : $this->provider_id),
            'price' => $this->price,
            'rest' => $this->rest,
            'ignored' => $this->ignored,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'manufacturer', $this->manufacturer]);

        return $dataProvider;
    }
}
