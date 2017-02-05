<?php

use yii\db\Migration;

class m170205_202425_mail_queue extends Migration
{
	public function up()
	{
		$this->createTable('mail_queue', [
			'id' => $this->primaryKey()->comment('ID'),
			'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус'),
			'to' => $this->string()->null()->comment('Кому'),
			'subject' => $this->string()->null()->comment('Тема'),
			'text' => $this->text()->null()->comment('Текст'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
	}

	public function down()
	{
		$this->dropTable('mail_queue');
	}
}
