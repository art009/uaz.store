<?php

use yii\db\Migration;

class m170319_084642_notice extends Migration
{
    public function up()
    {
	    $this->createTable('notice', [
		    'id' => $this->bigPrimaryKey()->unsigned()->comment('ID'),
		    'type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Тип'),
		    'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Статус'),
		    'user_id' => $this->integer()->comment('ID Пользователя'),
		    'data' => $this->text()->comment('Данные'),
		    'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
		    'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
	    ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

	    $this->addForeignKey('user_to_notice', 'notice', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
	    $this->dropTable('notice');
    }
}
