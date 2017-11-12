<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CatalogProductSearch represents the model behind the search form about `common\models\CatalogProduct`.
 */
class CatalogProductSearch extends CatalogProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'hide', 'on_main', 'length', 'width', 'height', 'weight', 'rest', 'external_id'], 'integer'],
            [['title', 'link', 'image', 'meta_keywords', 'meta_description', 'shop_title', 'provider_title', 'shop_code', 'provider_code', 'description', 'provider', 'manufacturer', 'unit', 'created_at', 'updated_at'], 'safe'],
            [['price_to', 'price_old'], 'number'],
            [['price', 'cart_counter'], 'match', 'pattern' => '/^(>|<|>=|<=|=|)(\s*[+-]?\d+\s*)$/'],
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
        $query = CatalogProduct::find()
			->joinWith(['categories'], false);

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
		$query->andFilterWhere(['=', $this::tableName() . '.id', $this->id]);
		$query->andFilterWhere(['=', $this::tableName() . '.hide', $this->hide]);
		$query->andFilterWhere(['=', $this::tableName() . '.on_main', $this->on_main]);
		$query->andFilterWhere(['=', $this::tableName() . '.external_id', $this->external_id]);

        if ($this->category_id) {
            $query->andFilterWhere(['catalog_product_to_category.category_id' => $this->category_id]);
        } else {
	        $query->andWhere('catalog_product_to_category.category_id IS NULL');
        }

        $query->andFilterCompare('price', $this->price);
        $query->andFilterCompare('cart_counter', $this->cart_counter);

        if (mb_strlen($this->image) > 0) {
            if ($this->image) {
                $query->andWhere(['is not', $this::tableName() . '.image', null]);
            } else {
                $query->andWhere(['is', $this::tableName() . '.image', null]);
            }
        }

        $query->andFilterWhere(['like', $this::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', $this::tableName() . '.link', $this->link])
            ->andFilterWhere(['like', $this::tableName() . '.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', $this::tableName() . '.meta_description', $this->meta_description])
            ->andFilterWhere(['like', $this::tableName() . '.shop_title', $this->shop_title])
            ->andFilterWhere(['like', $this::tableName() . '.provider_title', $this->provider_title])
            ->andFilterWhere(['like', $this::tableName() . '.shop_code', $this->shop_code])
            ->andFilterWhere(['like', $this::tableName() . '.provider_code', $this->provider_code])
            ->andFilterWhere(['like', $this::tableName() . '.description', $this->description])
            ->andFilterWhere(['like', $this::tableName() . '.provider', $this->provider])
            ->andFilterWhere(['like', $this::tableName() . '.manufacturer', $this->manufacturer])
            ->andFilterWhere(['like', $this::tableName() . '.unit', $this->unit]);

        $query->groupBy($this::tableName() . '.id');

        return $dataProvider;
    }
}
