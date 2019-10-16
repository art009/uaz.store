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
	public $tempStatus;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'vendor_code', 'title', 'unit', 'ignored', 'created_at', 'updated_at', 'tempStatus'], 'safe'],
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
	        //->joinWith(['providerItems']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
	        $this::tableName() . '.id' => $this->id,
	        $this::tableName() . '.price' => $this->price,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.code', $this->code])
            ->andFilterWhere(['like', $this::tableName() . '.vendor_code', $this->vendor_code])
            ->andFilterWhere(['like', $this::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', $this::tableName() . '.unit', $this->unit]);

        $cache = \Yii::$app->cache;
        if (!array_key_exists('tempStatus', $params[$this->formName()] ?? []) && $cache->exists('shop-item-search-status')) {
	        $this->tempStatus = $cache->get('shop-item-search-status');
        }
        if (array_key_exists($this->tempStatus, self::$statusList)) {
        	if (in_array($this->tempStatus, [
		        self::STATUS_WITHOUT_RELATION,
		        self::STATUS_WITHOUT_RELATION_AND_NOT_IGNORED,
		        self::STATUS_WITHOUT_RELATION_AND_NOT_FOUND,
	        ])) {
        		if ($this->tempStatus == self::STATUS_WITHOUT_RELATION_AND_NOT_FOUND) {
			        $query->andFilterWhere([$this::tableName() . '.status' => AppHelper::YES]);
		        } elseif ($this->tempStatus == self::STATUS_WITHOUT_RELATION_AND_NOT_IGNORED) {
			        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::NO]);
			        $query->andFilterWhere([$this::tableName() . '.status' => AppHelper::NO]);
		        } else {
			        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::YES]);
		        }
                $query->leftJoin('provider_item_to_shop_item', 'provider_item_to_shop_item.shop_item_id');
		        $query->andWhere('provider_item_to_shop_item.provider_item_id IS NULL');
	        } else {
        	    $query->leftJoin('provider_item_to_shop_item', 'provider_item_to_shop_item.shop_item_id');
		        $query->andWhere('provider_item_to_shop_item.provider_item_id IS NOT NULL');
	        }
        	if ($this->tempStatus == self::STATUS_ACTIVE) {
		        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::NO]);
		        $query->andWhere($this::tableName() . '.site_price > 0');
	        }
        	if ($this->tempStatus == self::STATUS_IGNORE) {
		        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::YES]);
	        }
        	if ($this->tempStatus == self::STATUS_PROFIT) {
		        $query->andWhere($this::tableName() . '.site_price > ' . $this::tableName() . '.price');
	        }
        	if ($this->tempStatus == self::STATUS_LOST) {
		        $query->andWhere($this::tableName() . '.site_price = 0');
	        }
        }
        $query->groupBy($this::tableName() . '.id');

	    $cache->set('shop-item-search-status', $this->tempStatus);

        return $dataProvider;
    }
}
