<?php

use yii\db\Migration;

class m170823_061738_provider_item extends Migration
{
	public function up()
	{
		$this->createTable('provider_item', [
			'id' => $this->primaryKey()->comment('ID'),
			'provider_id' => $this->integer()->comment('ID поставщика'),
			'code' => $this->string()->notNull()->comment('Код'),
			'vendor_code' => $this->string()->null()->comment('Артикул'),
			'title' => $this->string()->null()->comment('Название'),
			'price' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Цена'),
			'unit' => $this->string()->null()->comment('Единица измерения'),
			'manufacturer' => $this->string()->comment('Производитель'),
			'rest' => $this->integer()->defaultValue('0')->comment('Остаток'),
			'ignored' => $this->boolean()->defaultValue(0)->comment('Пропуск обновления'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->createIndex('unique_provider_code', 'provider_item', ['provider_id', 'code'], true);
	}

	public function down()
	{
		$this->dropTable('provider_item');
	}
}
