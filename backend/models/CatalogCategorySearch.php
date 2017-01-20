<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CatalogCategory;

/**
 * CatalogCategorySearch represents the model behind the search form about `common\models\CatalogCategory`.
 */
class CatalogCategorySearch extends CatalogCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hide'], 'integer'],
            [['parent_id', 'title', 'link', 'image', 'meta_keywords', 'meta_description', 'created_at', 'updated_at'], 'safe'],
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
        $query = CatalogCategory::find();

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
            'hide' => $this->hide,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if ($this->parent_id) {
            $query->andFilterWhere(['parent_id' => $this->parent_id]);
        } else {
            $query->andWhere(['parent_id' => null]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
