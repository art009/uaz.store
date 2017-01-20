<?php

use yii\db\Migration;

class m170115_183610_catalog_category extends Migration
{
    public function up()
    {
        $this->createTable('catalog_category', [
            'id' => $this->primaryKey()->comment('ID'),
            'parent_id' => $this->integer()->null()->comment('ID родительской категории'),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'link' => $this->string()->notNull()->unique()->comment('Ссылка'),
            'image' => $this->string()->null()->comment('Картинка'),
            'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
            'meta_description' => $this->text()->null()->comment('Текст метатега description'),
            'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрывать?'),
            'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
            'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->addForeignKey('parent_category', 'catalog_category', 'parent_id', 'catalog_category', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('catalog_category');
    }
}
