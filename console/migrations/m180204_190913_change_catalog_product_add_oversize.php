<?php

use yii\db\Migration;

/**
 * Class m180204_190913_change_catalog_product_add_oversized
 */
class m180204_190913_change_catalog_product_add_oversize extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
    	$this->addColumn('catalog_product', 'oversize', $this->boolean()->defaultValue(0)->comment('Крупногабаритный'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    	$this->dropColumn('catalog_product', 'oversize');
    }
}
