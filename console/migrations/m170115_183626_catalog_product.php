<?php

use yii\db\Migration;

class m170115_183626_catalog_product extends Migration
{
    public function up()
    {
        $this->createTable('catalog_product', [
            'id' => $this->primaryKey()->comment('ID'),
            'category_id' => $this->integer()->null()->comment('ID родительской категории'),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'link' => $this->string()->notNull()->unique()->comment('Ссылка'),
            'image' => $this->string()->null()->comment('Картинка'),
            'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
            'meta_description' => $this->text()->null()->comment('Текст метатега description'),
            'price' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Цена'),
            'price_to' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Цена до'),
            'price_old' => $this->decimal(10, 2)->defaultValue('0.00')->comment('Старая цена'),
            'shop_title' => $this->string()->null()->comment('Название в магазине'),
            'provider_title' => $this->string()->null()->comment('Название у поставщика'),
            'shop_code' => $this->string()->null()->comment('Артикул в магазине'),
            'provider_code' => $this->string()->null()->comment('Артикул у поставщика'),
            'description' => $this->text()->null()->comment('Описание'),
            'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрывать?'),
            'on_main' => $this->boolean()->notNull()->defaultValue(0)->comment('На главной странице?'),
            'provider' => $this->string()->null()->comment('Поставщик'),
            'manufacturer' => $this->string()->null()->comment('Производитель'),
            'cart_counter' => $this->integer()->unsigned()->defaultValue(0)->comment('Счетчик добавлений в корзину'),
            'length' => $this->integer()->unsigned()->defaultValue(0)->comment('Длина'),
            'width' => $this->integer()->unsigned()->defaultValue(0)->comment('Ширина'),
            'height' => $this->integer()->unsigned()->defaultValue(0)->comment('Высота'),
            'weight' => $this->integer()->unsigned()->defaultValue(0)->comment('Вес'),
            'unit' => $this->string()->null()->comment('Единица измерения'),
            'rest' => $this->integer()->unsigned()->defaultValue(0)->comment('Остаток'),
            'external_id' => $this->integer()->unsigned()->null()->comment('ID Поставщика'),
            'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
            'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->addForeignKey('product_category', 'catalog_product', 'category_id', 'catalog_category', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('catalog_product');
    }
}
