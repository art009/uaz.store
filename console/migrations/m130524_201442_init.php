<?php

use yii\db\Migration;

/**
 * Class m130524_201442_init
 */
class m130524_201442_init extends Migration
{
    public function up()
    {
        /**
         * TODO Переделать структуру пользователей
         */
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull(),
            'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
