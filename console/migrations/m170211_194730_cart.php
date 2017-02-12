<?php

use yii\db\Migration;

class m170211_194730_cart extends Migration
{
	public function up()
	{
		$this->createTable('cart', [
			'identity_id' => $this->string()->comment('ID корзины'),
			'product_id' => $this->integer()->comment('ID товара'),
			'quantity' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Количество'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addPrimaryKey('cart_primary', 'cart', ['identity_id', 'product_id']);
		$this->addForeignKey('cart_product', 'cart', 'product_id', 'catalog_product', 'id');
	}

	public function down()
	{
		$this->dropTable('cart');
	}
}
