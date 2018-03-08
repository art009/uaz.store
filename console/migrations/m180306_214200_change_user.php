<?php

use yii\db\Migration;

/**
 * Class m180306_214200_change_user
 */
class m180306_214200_change_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'passport_series', $this->smallInteger(4)->unsigned()->defaultValue(null)->null()->comment('Серия паспорта'));
        $this->alterColumn('user', 'passport_number', $this->integer(6)->unsigned()->defaultValue(null)->null()->comment('Номер паспорта'));
        $this->alterColumn('user', 'inn', $this->bigInteger()->unsigned()->defaultValue(null)->null()->comment('ИНН'));
        $this->alterColumn('user', 'kpp', $this->integer(9)->unsigned()->defaultValue(null)->null()->comment('КПП'));
        $this->alterColumn('user', 'postcode', $this->integer(6)->unsigned()->defaultValue(null)->null()->comment('Почтовый индекс'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'passport_series', $this->smallInteger(4)->unsigned()->defaultValue(0)->notNull()->comment('Серия паспорта'));
        $this->alterColumn('user', 'passport_number', $this->integer(6)->unsigned()->defaultValue(0)->notNull()->comment('Номер паспорта'));
        $this->alterColumn('user', 'inn', $this->bigInteger()->unsigned()->defaultValue(0)->notNull()->comment('ИНН'));
        $this->alterColumn('user', 'kpp', $this->integer(9)->unsigned()->defaultValue(0)->notNull()->comment('КПП'));
        $this->alterColumn('user', 'postcode', $this->integer(6)->unsigned()->defaultValue(0)->notNull()->comment('Почтовый индекс'));
    }
}
