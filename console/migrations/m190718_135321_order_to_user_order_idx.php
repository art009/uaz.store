<?php

use yii\db\Migration;

/**
 * Class m190718_135321_order_to_user_order_idx
 */
class m190718_135321_order_to_user_order_idx extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE `order` SET user_id = original_user_id");
        foreach (\common\models\User::find()->each() as $user) {
            $userOrder = new \common\models\UserOrder();
            $userOrder->copyFromUser($user);
            $userOrder->id = $user->id;
            $userOrder->save();
        }
        $this->addForeignKey('order_user_to_order', 'order', 'user_id', 'user_order', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190718_135321_order_to_user_order_idx cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190718_135321_order_to_user_order_idx cannot be reverted.\n";

        return false;
    }
    */
}
