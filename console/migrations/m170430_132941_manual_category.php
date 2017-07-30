<?php

use yii\db\Migration;

class m170430_132941_manual_category extends Migration
{
	public function up()
	{
		$this->createTable('manual_category', [
			'id' => $this->primaryKey()->comment('ID'),
			'manual_id' => $this->integer()->null()->comment('ID справочника'),
			'parent_id' => $this->integer()->null()->comment('ID родительской категории'),
			'catalog_category_id' => $this->integer()->null()->comment('ID категории каталога'),
			'title' => $this->string()->notNull()->comment('Заголовок'),
			'link' => $this->string()->notNull()->comment('Ссылка'),
			'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрывать?'),
			'image' => $this->string()->null()->comment('Картинка'),
			'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
			'meta_description' => $this->text()->null()->comment('Текст метатега description'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->createIndex('link_in_catalog', 'manual_category', ['manual_id', 'link'], true);

		$this->addForeignKey('manual_category_parent_manual', 'manual_category', 'manual_id', 'manual', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('manual_category_parent', 'manual_category', 'parent_id', 'manual_category', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('manual_category_catalog_category', 'manual_category', 'catalog_category_id', 'catalog_category', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropTable('manual_category');
	}
}
