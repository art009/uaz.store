<?php

use yii\db\Migration;

/**
 * Class m190718_143250_recreate_user_model
 */
class m190718_143250_recreate_user_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('order_user_to_order', 'order');
        $this->dropTable('{{%user_order}}');
        $this->createTable('{{%user_order}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(),
            'phone' => 'CHAR(10)',
            'legal' => $this->smallInteger(),
            'name' => $this->string(),
            'passport_series' => 'CHAR(4)',
            'passport_number' => 'CHAR(6)',
            'inn' => 'CHAR(12)',
            'kpp' => 'CHAR(9)',
            'postcode' => 'CHAR(6)',
            'address' => $this->string(),
            'fax' => $this->string(),
            'representive_name' => $this->string(),
            'representive_position' => $this->string(),
            'bank_name' => $this->string(),
            'bik' => $this->string(),
            'account_number' => $this->string(),
            'correspondent_account' => $this->string(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        foreach (\common\models\User::find()->each() as $user) {
            $userOrder = new \common\models\UserOrder();
            $userOrder->copyFromUser($user);
            $userOrder->id = $user->id;
            $userOrder->save();
        }
        $this->execute("UPDATE `order` SET user_id = original_user_id");
        $this->addForeignKey('order_user_to_order', 'order', 'user_id', 'user_order', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190718_143250_recreate_user_model cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190718_143250_recreate_user_model cannot be reverted.\n";

        return false;
    }
    */
}
