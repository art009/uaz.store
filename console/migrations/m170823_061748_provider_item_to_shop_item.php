<?php

use yii\db\Migration;

class m170823_061748_provider_item_to_shop_item extends Migration
{
	public function up()
	{
		$this->createTable('provider_item_to_shop_item', [
			'shop_item_id' => $this->integer()->notNull()->comment('ID товара магазина'),
			'provider_item_id' => $this->integer()->notNull()->comment('ID товара поставщика'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addForeignKey('shop_item_key', 'provider_item_to_shop_item', 'shop_item_id', 'shop_item', 'id');
		$this->addForeignKey('provider_item_key', 'provider_item_to_shop_item', 'provider_item_id', 'provider_item', 'id');

		$this->createIndex('unique_provider_shop_relation', 'provider_item_to_shop_item', ['shop_item_id', 'provider_item_id'], true);
	}

	public function down()
	{
		$this->dropTable('provider_item_to_shop_item');
	}
}
