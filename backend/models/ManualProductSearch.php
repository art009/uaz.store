<?php

namespace backend\models;

use common\models\ManualProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ManualProductSearch represents the model behind the search form of `common\models\ManualProduct`.
 */
class ManualProductSearch extends ManualProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'manual_category_id', 'product_id', 'left', 'top', 'width', 'height', 'hide'], 'integer'],
            [['number', 'code', 'title', 'positions', 'created_at', 'updated_at'], 'safe'],
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
        $query = ManualProduct::find();

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
            'manual_category_id' => $this->manual_category_id,
            'product_id' => $this->product_id,
            'left' => $this->left,
            'top' => $this->top,
            'width' => $this->width,
            'height' => $this->height,
            'hide' => $this->hide,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'positions', $this->positions]);

        return $dataProvider;
    }
}
