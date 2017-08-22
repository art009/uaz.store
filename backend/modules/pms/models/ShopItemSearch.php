<?php

namespace app\modules\pms\models;

use common\components\AppHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShopItemSearch represents the model behind the search form about `app\modules\pms\models\ShopItem`.
 */
class ShopItemSearch extends ShopItem
{
	/**
	 * @var int
	 */
	public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'vendor_code', 'title', 'unit', 'ignored', 'created_at', 'updated_at', 'status'], 'safe'],
            [['price', 'percent', 'site_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = ShopItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'unit', $this->unit]);

        if (array_key_exists($this->status, self::$statusList)) {
        	if ($this->status == self::STATUS_ACTIVE) {
		        $query->andFilterWhere(['ignored' => AppHelper::NO]);
		        $query->andWhere('site_price > 0');
	        }
        	if ($this->status == self::STATUS_IGNORE) {
		        $query->andFilterWhere(['ignored' => AppHelper::YES]);
	        }
        	if ($this->status == self::STATUS_PROFIT) {
		        $query->andWhere('site_price > price');
	        }
        	if ($this->status == self::STATUS_LOST) {
		        $query->andWhere('site_price = 0');
	        }
        }

        return $dataProvider;
    }
}
