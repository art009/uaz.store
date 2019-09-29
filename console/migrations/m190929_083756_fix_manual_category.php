<?php

use yii\db\Migration;

/**
 * Class m190929_083756_fix_manual_category
 */
class m190929_083756_fix_manual_category extends Migration
{
    public $mapping = [
        5 => 73,
        35 => 93,
        120 => 150,
        122 => 150,
        124 => 149,
        144 => 118,
        146 => 118,
        148 => 118,
        200 => 73,
        220 => 90,
        230 => 93,
        266 => 103,
        288 => 157,
        299 => 145,
        305 => 148,
        308 => 150,
        309 => 150,
        311 => 150,
        312 => 150,
        319 => 149,
        327 => 130,
        329 => 130,
        331 => 131,
        333 => 131,
        336 => 132,
        337 => 131,
        339 => 132,
        341 => 130,
        348 => 118,
        349 => 118,
        351 => 118,
        352 => 118,
        354 => 118,
        355 => 118,
        358 => 119,
        359 => 119,

    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190929_083756_fix_manual_category cannot be reverted.\n";

        return false;
    }
}
