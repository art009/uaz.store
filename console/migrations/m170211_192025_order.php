<?php

use yii\db\Migration;

class m170211_192025_order extends Migration
{
	public function up()
	{
		$this->createTable('order', [
			'id' => $this->bigPrimaryKey()->unsigned()->comment('ID'),
			'user_id' => $this->integer()->comment('ID Пользователя'),
			'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Статус'),
			'sum' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('Стоимость'),
			'delivery_sum' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('Стоимость доставки'),
			'delivery_type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Способ доставки'),
			'payment_type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Метод оплаты'),
			'changed_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время изменения статуса'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addForeignKey('user_to_order', 'order', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
	}

    public function down()
    {
        $this->dropTable('order');
    }
}
