<?php

use yii\db\Migration;

class m170430_132917_manual extends Migration
{
	public function up()
	{
		$this->execute("DROP TABLE IF EXISTS catalog_manual_page;");
		$this->execute("DROP TABLE IF EXISTS catalog_manual;");

		$this->createTable('manual', [
			'id' => $this->primaryKey()->comment('ID'),
			'title' => $this->string()->notNull()->comment('Заголовок'),
			'link' => $this->string()->notNull()->unique()->comment('Ссылка'),
			'image' => $this->string()->null()->comment('Картинка'),
			'description' => $this->text()->null()->comment('Описание'),
			'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
			'meta_description' => $this->text()->null()->comment('Текст метатега description'),
			'year' => $this->smallInteger()->null()->comment('Год выпуска'),
			'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрывать?'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
	}

	public function down()
	{
		$this->dropTable('manual');
	}
}
