<?php

use yii\db\Migration;

/**
 * Class m190929_101958_fix_manual_category_to_null
 */
class m190929_101958_fix_manual_category_to_null extends Migration
{
    public $mapping = [
        541,
        554,
        575,
        594,
        906,
        913,
        929,
        941,
        1116,
        1124,
        1147
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach ($this->mapping as $manualCategoryId) {
            $this->update('manual_category', ['catalog_category_id' => null], ['id' => $manualCategoryId]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190929_101958_fix_manual_category_to_null cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190929_101958_fix_manual_category_to_null cannot be reverted.\n";

        return false;
    }
    */
}
