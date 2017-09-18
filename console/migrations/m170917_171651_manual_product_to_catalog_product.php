<?php

use yii\db\Migration;

class m170917_171651_manual_product_to_catalog_product extends Migration
{
	public function up()
	{
		$this->createTable('manual_product_to_catalog_product', [
			'manual_product_id' => $this->integer()->null()->comment('ID товара справочника'),
			'catalog_product_id' => $this->integer()->null()->comment('ID товара каталога'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addForeignKey('manual_product_to_catalog_product_to_manual', 'manual_product_to_catalog_product', 'manual_product_id', 'manual_product', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('manual_product_to_catalog_product_to_catalog', 'manual_product_to_catalog_product', 'catalog_product_id', 'catalog_product', 'id', 'CASCADE', 'CASCADE');

		$this->createIndex('unique_manual_catalog_product', 'manual_product_to_catalog_product', ['manual_product_id', 'catalog_product_id'], true);
	}

	public function down()
	{
		$this->dropTable('manual_product_to_catalog_product');
	}
}
