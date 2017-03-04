<?php

namespace backend\models;

use common\behaviors\SortableBehavior;
use Yii;
use yii\caching\TagDependency;

/**
 * Class Menu
 *
 * @package backend\models
 */
class Menu extends \common\models\Menu
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

	    TagDependency::invalidate(Yii::$app->cache, self::CACHE_TAG);
    }
}
