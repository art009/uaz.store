<?php

use yii\db\Migration;

class m170204_150603_user extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `user`;');

		$this->createTable('user', [
			'id' => $this->primaryKey()->comment('ID'),
			'email' => $this->string()->null()->unique()->comment('E-mail'),
			'phone' => $this->char(10)->null()->unique()->comment('Телефон'),
			'auth_key' => $this->string(32)->notNull()->comment('Auth Key'),
			'password_hash' => $this->string()->notNull()->comment('Хеш пароля'),
			'password_reset_token' => $this->string()->unique()->comment('Токен сброса пароля'),
			'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('Статус'),
			'role' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Роль'),
			'legal' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Физ/Юр лицо'),
			'name' => $this->string()->null()->comment('ФИО/Название компании'),
			'passport_series' => $this->smallInteger(4)->notNull()->unsigned()->defaultValue(0)->comment('Серия паспорта'),
			'passport_number' => $this->integer(6)->notNull()->unsigned()->defaultValue(0)->comment('Номер паспорта'),
			'inn' => $this->bigInteger()->notNull()->unsigned()->defaultValue(0)->comment('ИНН'),
			'kpp' => $this->integer(9)->notNull()->unsigned()->defaultValue(0)->comment('КПП'),
			'postcode' => $this->integer(6)->notNull()->unsigned()->defaultValue(0)->comment('Почтовый индекс'),
			'address' => $this->string()->null()->comment('Полный адрес'),
			'fax' => $this->string()->null()->comment('Факс'),
			'photo' => $this->string()->null()->comment('Фотография'),
			'offer_accepted' => $this->boolean()->notNull()->defaultValue(0)->comment('Согласие с офертой'),
			'accepted_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время согласия'),
			'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
			'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
		], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

		$this->insert('user', [
			'email' => 'admin@uaz.store',
			'auth_key' => '9nfYaNcvaPh1Rtwdt8gcmBQEHZfVxNrd',
			'password_hash' => '$2y$13$0hrpeHEjLZ6HHOPCkL0Pwe8osAe/4tymGZEqmwGGQKVRYxTgG7WKC',
			'role' => 1,
			'created_at' => '2017-01-15 11:28:37',
			'updated_at' => '2017-01-15 11:28:37',
		]);
    }

    public function down()
    {
		$this->dropTable('user');
    }
}
