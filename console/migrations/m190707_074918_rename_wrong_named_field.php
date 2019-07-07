<?php

use yii\db\Migration;

/**
 * Class m190707_074918_rename_wrong_named_field
 */
class m190707_074918_rename_wrong_named_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('user', 'repsesentive_position', 'representive_position');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190707_074918_rename_wrong_named_field cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190707_074918_rename_wrong_named_field cannot be reverted.\n";

        return false;
    }
    */
}
