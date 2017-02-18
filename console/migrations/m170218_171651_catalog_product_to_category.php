<?php

use yii\db\Migration;

class m170218_171651_catalog_product_to_category extends Migration
{
	public function up()
	{
		$this->createTable('catalog_product_to_category', [
			'product_id' => $this->integer()->null()->comment('ID товара'),
			'category_id' => $this->integer()->null()->comment('ID категории'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addForeignKey('parent_catalog_product', 'catalog_product_to_category', 'product_id', 'catalog_product', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('parent_catalog_category', 'catalog_product_to_category', 'category_id', 'catalog_category', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropTable('catalog_product_to_category');
	}
}
