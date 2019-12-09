<?php

use yii\db\Migration;

class m191208_151403_news extends Migration
{
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey()->comment('ID'),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'image' => $this->string()->null()->comment('Картинка'),
            'description' => $this->text()->null()->comment('Текст'),
            'meta_keywords' => $this->text()->null()->comment('Текст метатега keywords'),
            'meta_description' => $this->text()->null()->comment('Текст метатега description'),
            'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Показана/Скрыта'),
            'created_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время создания'),
            'updated_at' => $this->timestamp()->defaultValue('0000-00-00 00:00:00')->notNull()->comment('Время обновления'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('news');
    }
}
