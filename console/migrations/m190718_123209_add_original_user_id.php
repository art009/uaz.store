<?php

use yii\db\Migration;

/**
 * Class m190718_123209_add_original_user_id
 */
class m190718_123209_add_original_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'original_user_id', $this->integer());
        $this->addForeignKey('original_user_to_order', 'order', 'original_user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->execute("UPDATE `order` SET original_user_id = user_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190718_123209_add_original_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190718_123209_add_original_user_id cannot be reverted.\n";

        return false;
    }
    */
}
