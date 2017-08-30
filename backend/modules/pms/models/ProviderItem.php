<?php

namespace app\modules\pms\models;

use Yii;

/**
 * This is the model class for table "provider_item".
 *
 * @property integer $id
 * @property integer $provider_id
 * @property string $code
 * @property string $vendor_code
 * @property string $title
 * @property string $price
 * @property string $unit
 * @property string $manufacturer
 * @property integer $rest
 * @property integer $ignored
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ProviderShopItem[] $providerShopItems
 * @property ShopItem[] $shopItems
 * @property Provider $provider
 */
class ProviderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider_id', 'rest', 'ignored'], 'integer'],
            [['code'], 'required'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'vendor_code', 'title', 'unit', 'manufacturer'], 'string', 'max' => 255],
            [['provider_id', 'code'], 'unique', 'targetAttribute' => ['provider_id', 'code'], 'message' => 'The combination of ID поставщика and Код has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider_id' => 'ID поставщика',
            'code' => 'Код',
            'vendor_code' => 'Артикул',
            'title' => 'Название',
            'price' => 'Цена',
            'unit' => 'Единица измерения',
            'manufacturer' => 'Производитель',
            'rest' => 'Остаток',
            'ignored' => 'Пропуск обновления',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProviderShopItems()
    {
        return $this->hasMany(ProviderShopItem::className(), ['provider_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItems()
    {
        return $this->hasMany(ShopItem::className(), ['id' => 'shop_item_id'])->viaTable('provider_item_to_shop_item', ['provider_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
	    return $this->hasOne(Provider::className(), ['id' => 'provider_id']);
    }

    /**
     * @inheritdoc
     * @return ProviderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProviderItemQuery(get_called_class());
    }

	/**
	 * @param $id
	 *
	 * @return bool
	 */
    public function checkShopItemLink($id)
    {
    	$existedIds = $this->getShopItems()->select(['id'])->column();

    	return in_array($id, $existedIds);
    }

	/**
	 * @param int $shopItemId
	 *
	 * @return int
	 */
    public function getLinkQuantity(int $shopItemId)
    {
    	return (int)ProviderShopItem::find()
		    ->select('quantity')
		    ->where([
		    	'shop_item_id' => $shopItemId,
		    	'provider_item_id' => $this->id,
		    ])
		    ->scalar();
    }
}
