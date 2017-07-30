<?php

use yii\db\Migration;

class m170430_133020_manual_product extends Migration
{
	public function up()
	{
		$this->createTable('manual_product', [
			'id' => $this->primaryKey()->comment('ID'),
			'manual_category_id' => $this->integer()->null()->comment('ID страницы справочника'),
			'product_id' => $this->integer()->null()->comment('ID товара каталога'),
			'number' => $this->string()->null()->comment('Номер на чертеже'),
			'code' => $this->string()->null()->comment('Артикул завода'),
			'title' => $this->string()->null()->comment('Название'),
			'left' => $this->integer()->null()->comment('Отступ слева'),
			'top' => $this->integer()->null()->comment('Отступ сверху'),
			'width' => $this->integer()->null()->comment('Ширина'),
			'height' => $this->integer()->null()->comment('Высота'),
			'positions' => $this->text()->null()->comment('Дополнительные позиции'),
			'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрывать?'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addForeignKey('manual_product_parent_manual_category', 'manual_product', 'manual_category_id', 'manual_category', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('manual_product_catalog_product', 'manual_product', 'product_id', 'catalog_product', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropTable('manual_product');
	}
}
