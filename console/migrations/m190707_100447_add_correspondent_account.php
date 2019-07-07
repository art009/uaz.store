<?php

use yii\db\Migration;

/**
 * Class m190707_100447_add_correspondent_account
 */
class m190707_100447_add_correspondent_account extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'correspondent_account', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190707_100447_add_correspondent_account cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190707_100447_add_correspondent_account cannot be reverted.\n";

        return false;
    }
    */
}
