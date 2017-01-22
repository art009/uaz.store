<?php

namespace backend\models;

use Yii;
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
        $query = CatalogProduct::find();

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
            'price_to' => $this->price_to,
            'price_old' => $this->price_old,
            'hide' => $this->hide,
            'on_main' => $this->on_main,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
            'rest' => $this->rest,
            'external_id' => $this->external_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if ($this->category_id) {
            $query->andFilterWhere(['category_id' => $this->category_id]);
        } else {
            $query->andWhere(['category_id' => null]);
        }

        $query->andFilterCompare('price', $this->price);
        $query->andFilterCompare('cart_counter', $this->cart_counter);

        if (mb_strlen($this->image) > 0) {
            if ($this->image) {
                $query->andWhere(['is not', 'image', null]);
            } else {
                $query->andWhere(['is', 'image', null]);
            }
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'shop_title', $this->shop_title])
            ->andFilterWhere(['like', 'provider_title', $this->provider_title])
            ->andFilterWhere(['like', 'shop_code', $this->shop_code])
            ->andFilterWhere(['like', 'provider_code', $this->provider_code])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'provider', $this->provider])
            ->andFilterWhere(['like', 'manufacturer', $this->manufacturer])
            ->andFilterWhere(['like', 'unit', $this->unit]);

        return $dataProvider;
    }
}
