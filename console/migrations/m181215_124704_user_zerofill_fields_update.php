<?php

use yii\db\Migration;

/**
 * Class m181215_124704_user_zerofill_fields_update
 */
class m181215_124704_user_zerofill_fields_update extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->alterColumn('user', 'passport_series', $this->char(4)->null()->comment('Серия паспорта'));
		$this->alterColumn('user', 'passport_number', $this->char(6)->null()->comment('Номер паспорта'));
		$this->alterColumn('user', 'inn', $this->char(12)->null()->comment('ИНН'));
		$this->alterColumn('user', 'kpp', $this->char(9)->null()->comment('КПП'));
		$this->alterColumn('user', 'postcode', $this->char(6)->null()->comment('Почтовый индекс'));
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->alterColumn('user', 'passport_series', $this->smallInteger(4)->unsigned()->defaultValue(null)->null()->comment('Серия паспорта'));
		$this->alterColumn('user', 'passport_number', $this->integer(6)->unsigned()->defaultValue(null)->null()->comment('Номер паспорта'));
		$this->alterColumn('user', 'inn', $this->bigInteger()->unsigned()->defaultValue(null)->null()->comment('ИНН'));
		$this->alterColumn('user', 'kpp', $this->integer(9)->unsigned()->defaultValue(null)->null()->comment('КПП'));
		$this->alterColumn('user', 'postcode', $this->integer(6)->unsigned()->defaultValue(null)->null()->comment('Почтовый индекс'));
	}
}
