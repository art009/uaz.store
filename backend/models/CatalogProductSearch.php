<?php

namespace backend\models;

use common\components\AppHelper;
use Minify\App;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CatalogProductSearch represents the model behind the search form about `common\models\CatalogProduct`.
 */
class CatalogProductSearch extends CatalogProduct
{
	/** @var array  */
	public $excludedIds = [];

	public $hasCategories;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'hide', 'on_main', 'length', 'width', 'height', 'weight', 'rest'], 'integer'],
            [['title', 'link', 'image', 'meta_keywords', 'meta_description', 'shop_title', 'provider_title',
	            'shop_code', 'provider_code', 'description', 'provider', 'manufacturer', 'unit', 'external_id',
	            'excludedIds', 'hasCategories'
            ], 'safe'],
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
     * @param int $pageSize
     *
     * @return ActiveDataProvider
     */
    public function search($params, int $pageSize = 20)
    {
        $query = CatalogProduct::find()
            ->select([self::tableName().'.*', "COUNT(catalog_category.id) as categoryCount"])
			->joinWith(['categories'], false);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'pagination' => [
	        	'pageSize' => $pageSize,
	        ],
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

        if ($this->category_id) {
            $query->andFilterWhere(['catalog_product_to_category.category_id' => $this->category_id]);
        }

        if ($this->hasCategories !== "") {
            if ($this->hasCategories == AppHelper::NO) {
                $query->having(['=', 'categoryCount', 0]);
            } else {
                $query->having(['>', 'categoryCount', 0]);
            }
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

        if ($this->excludedIds) {
	        $query->andFilterWhere(['not in', $this::tableName() . '.id', $this->excludedIds]);
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
            ->andFilterWhere(['like', $this::tableName() . '.unit', $this->unit])
            ->andFilterWhere(['like', $this::tableName() . '.external_id', $this->external_id]);

        $query->groupBy($this::tableName() . '.id');

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'title',
                'image',
                'price',
                'cart_counter',
                'hasCategories' => [
                    'asc' => ['categoryCount' => SORT_ASC ],
                    'desc' => ['categoryCount' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'hide'
            ]
        ]);

        return $dataProvider;
    }
}
