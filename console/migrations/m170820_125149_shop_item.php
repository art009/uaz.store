<?php

use yii\db\Migration;

/**
 * Class m170820_125149_shop_item
 */
class m170820_125149_shop_item extends Migration
{
    public function up()
    {
	    $this->createTable('shop_item', [
		    'id' => $this->primaryKey()->comment('ID'),
		    'code' => $this->string()->unique()->notNull()->comment('Код'),
		    'vendor_code' => $this->string()->null()->comment('Артикул'),
		    'title' => $this->string()->null()->comment('Название'),
		    'price' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Цена'),
		    'percent' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Процент накрутки'),
		    'site_price' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Цена для сайта'),
		    'unit' => $this->string()->null()->comment('Единица измерения'),
		    'ignored' => $this->boolean()->defaultValue(0)->comment('Пропуск обновления'),
		    'status' => $this->smallInteger()->defaultValue(0)->comment('Статус'),
		    'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
		    'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
	    ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    public function down()
    {
	    $this->dropTable('shop_item');
    }
}
