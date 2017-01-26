<?php

use yii\db\Migration;

class m170126_175722_catalog_manual_category extends Migration
{
	public function up()
	{
		$this->createTable('catalog_manual_category', [
			'id' => $this->primaryKey()->comment('ID'),
			'manual_id' => $this->integer()->null()->comment('ID справочника'),
			'category_id' => $this->integer()->null()->comment('ID категории'),
			'image' => $this->string()->null()->comment('Картинка'),
			'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
			'meta_description' => $this->text()->null()->comment('Текст метатега description'),
			'description' => $this->text()->null()->comment('Текст для справочника'),
			'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрывать?'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->addForeignKey('parent_manual', 'catalog_manual_category', 'manual_id', 'catalog_manual', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('parent_manual_category', 'catalog_manual_category', 'category_id', 'catalog_category', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropTable('catalog_manual_category');
	}
}
