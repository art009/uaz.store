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
        $query = ShopItem::find()
	        ->joinWith(['providerItems']);

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
        if (!array_key_exists('status', $params[$this->formName()] ?? []) && $cache->exists('shop-item-search-status')) {
	        $this->status = $cache->get('shop-item-search-status');
        }
        if (array_key_exists($this->status, self::$statusList)) {
        	$cache->set('shop-item-search-status', $this->status);
        	if ($this->status == self::STATUS_WITHOUT_RELATION || $this->status == self::STATUS_WITHOUT_RELATION_AND_NOT_IGNORED) {
        		if ($this->status == self::STATUS_WITHOUT_RELATION_AND_NOT_IGNORED) {
			        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::NO]);
		        } else {
			        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::YES]);
		        }
		        $query->andWhere(ProviderItem::tableName() . '.id IS NULL');
	        } else {
		        $query->andWhere(ProviderItem::tableName() . '.id IS NOT NULL');
	        }
        	if ($this->status == self::STATUS_ACTIVE) {
		        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::NO]);
		        $query->andWhere($this::tableName() . '.site_price > 0');
	        }
        	if ($this->status == self::STATUS_IGNORE) {
		        $query->andFilterWhere([$this::tableName() . '.ignored' => AppHelper::YES]);
	        }
        	if ($this->status == self::STATUS_PROFIT) {
		        $query->andWhere($this::tableName() . '.site_price > ' . $this::tableName() . '.price');
	        }
        	if ($this->status == self::STATUS_LOST) {
		        $query->andWhere($this::tableName() . '.site_price = 0');
	        }
        }

        return $dataProvider;
    }
}
