<?php

use yii\db\Migration;

class m170115_183632_catalog_product_image extends Migration
{
    public function up()
    {
        $this->createTable('catalog_product_image', [
            'id' => $this->primaryKey()->comment('ID'),
            'product_id' => $this->integer()->null()->comment('ID родительского товара'),
            'image' => $this->string()->null()->comment('Картинка'),
            'main' => $this->boolean()->notNull()->defaultValue(0)->comment('Главная?'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->addForeignKey('parent_product', 'catalog_product_image', 'product_id', 'catalog_product', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('catalog_product_image');
    }
}
