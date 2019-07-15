<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "catalog_product_similar".
 *
 * @property int $id
 * @property int $product_id
 * @property int $similar_product_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CatalogProduct $product
 * @property CatalogProduct $similarProduct
 */
class CatalogProductSimilar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catalog_product_similar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'similar_product_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Товар',
            'similar_product_id' => 'Аналогичный товар',
            'created_at' => 'Добавлен',
            'updated_at' => 'Отредактирован',
            'similarProductTitle' => 'Аналогичный товар',
            'productTitle' => 'Товар',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public function saveSimilar()
    {
        try {
            $similarProducts = $this->similarProduct->similarProducts;
            $ids = [$this->product->id];
            foreach ($similarProducts as $similarProduct) {
                $ids[] = $similarProduct->id;
            }
            $ids = array_unique($ids);
            $this->similarProduct->setSimilarProducts($ids);
        } catch (\Exception $e) {
            //do nothing
        }
    }

    public function beforeDelete()
    {
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimilarProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'similar_product_id']);
    }

    public function getProductTitle()
    {
        if (sizeof($this->product->categories) > 0) {
            $url = Yii::$app->params['mainDomain'] . "/catalog/product?id={$this->product->id}&categoryId={$this->product->categories[0]->id}";
            return \yii\helpers\Html::a($this->product->title, $url, ['class' => 'open-catalog', 'target' => '_blank']);
        }

        return $this->product->title;
    }

    public function getSimilarProductTitle()
    {
        if (sizeof($this->similarProduct->categories) > 0) {
            $url = Yii::$app->params['mainDomain'] . "/catalog/product?id={$this->similarProduct->id}&categoryId={$this->similarProduct->categories[0]->id}";
            return \yii\helpers\Html::a($this->similarProduct->title, $url, ['class' => 'open-catalog', 'target' => '_blank']);
        }

        return $this->similarProduct->title;
    }
}
