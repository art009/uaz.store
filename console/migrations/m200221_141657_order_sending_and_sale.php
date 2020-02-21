<?php

use yii\db\Migration;

/**
 * Class m200221_141657_order_sending_and_sale
 */
class m200221_141657_order_sending_and_sale extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'sending_cost', $this->decimal(10, 2)->after('payment_id'));
        $this->addColumn('order', 'sale_percent', $this->tinyInteger(2)->after('sending_cost'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'sending_cost');
        $this->dropColumn('order', 'sale_percent');
    }
}
