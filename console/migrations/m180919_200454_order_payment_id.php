<?php

use yii\db\Migration;

/**
 * Class m180919_200454_order_payment_id
 */
class m180919_200454_order_payment_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->addColumn('order', 'payment_id', $this->string()->null()->comment('ID платежа')->after('payment_type'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropColumn('order', 'payment_id');
    }
}
