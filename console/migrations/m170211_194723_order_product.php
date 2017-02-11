<?php

use yii\db\Migration;

class m170211_194723_order_product extends Migration
{
	public function up()
	{
		$this->createTable('order_product', [
			'order_id' => $this->bigInteger()->unsigned()->comment('ID заказа'),
			'product_id' => $this->integer()->comment('ID товара'),
			'price' => $this->decimal(10, 2)->notNull()->defaultValue('0.00')->comment('Стоимость товара'),
			'quantity' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Количество'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addPrimaryKey('order_product_primary', 'order_product', ['order_id', 'product_id']);
		$this->addForeignKey('product_order', 'order_product', 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('product_product', 'order_product', 'product_id', 'catalog_product', 'id');
	}

	public function down()
	{
		$this->dropTable('order_product');
	}
}
