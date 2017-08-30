<?php

namespace app\modules\pms\models;

use Yii;

/**
 * This is the model class for table "provider_item_to_shop_item".
 *
 * @property integer $shop_item_id
 * @property integer $provider_item_id
 * @property integer $quantity
 *
 * @property ProviderItem $providerItem
 * @property ShopItem $shopItem
 */
class ProviderShopItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider_item_to_shop_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_item_id', 'provider_item_id'], 'required'],
            [['shop_item_id', 'provider_item_id', 'quantity'], 'integer'],
            [['shop_item_id', 'provider_item_id'], 'unique', 'targetAttribute' => ['shop_item_id', 'provider_item_id'], 'message' => 'The combination of ID товара магазина and ID товара поставщика has already been taken.'],
            [['provider_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProviderItem::className(), 'targetAttribute' => ['provider_item_id' => 'id']],
            [['shop_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopItem::className(), 'targetAttribute' => ['shop_item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shop_item_id' => 'ID товара магазина',
            'provider_item_id' => 'ID товара поставщика',
            'quantity' => 'Кол-во единиц товара поставщика',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProviderItem()
    {
        return $this->hasOne(ProviderItem::className(), ['id' => 'provider_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItem()
    {
        return $this->hasOne(ShopItem::className(), ['id' => 'shop_item_id']);
    }

    /**
     * @inheritdoc
     * @return ProviderShopItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProviderShopItemQuery(get_called_class());
    }
}
