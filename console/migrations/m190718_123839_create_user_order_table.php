<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_order}}`.
 */
class m190718_123839_create_user_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_order}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(),
            'phone' => 'CHAR(10)',
            'legal' => $this->smallInteger(),
            'name' => $this->string(),
            'passport_series' => 'CHAR(4)',
            'passport_number' => 'CHAR(6)',
            'inn' => 'CHAR(12)',
            'kpp' => 'CHAR(9)',
            'postcode' => 'CHAR(6)',
            'address' => $this->string(),
            'fax' => $this->string(),
            'representive_name' => $this->string(),
            'representive_position' => $this->string(),
            'bank_name' => $this->string(),
            'bik' => $this->string(),
            'account_number' => $this->string(),
            'correspondent_account' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_order}}');
    }
}
