<?php

use yii\db\Migration;

class m170115_084853_page extends Migration
{
    public function up()
    {
        $this->createTable('page', [
            'id' => $this->primaryKey()->comment('ID'),
            'parent_id' => $this->integer()->null()->comment('ID родительской страницы'),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'link' => $this->string()->notNull()->unique()->comment('Ссылка'),
            'description' => $this->text()->null()->comment('Текст'),
            'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
            'meta_description' => $this->text()->null()->comment('Текст метатега description'),
            'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Показана/Скрыта'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->addForeignKey('parent_page', 'page', 'parent_id', 'page', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('page');
    }
}
