<?php

use yii\db\Migration;

/**
 * Class m190706_110716_add_business_fields_to_users
 */
class m190706_110716_add_business_fields_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'representive_name', $this->string());
        $this->addColumn('user', 'repsesentive_position', $this->string());
        $this->addColumn('user', 'bank_name', $this->string());
        $this->addColumn('user', 'bik', $this->string());
        $this->addColumn('user', 'account_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190706_110716_add_business_fields_to_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190706_110716_add_business_fields_to_users cannot be reverted.\n";

        return false;
    }
    */
}
