<?php

use yii\db\Migration;

class m170115_084846_menu extends Migration
{
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey()->comment('ID'),
            'parent_id' => $this->integer()->null()->comment('ID родительского пункта'),
            'title' => $this->string()->notNull()->comment('Заголовок'),
            'link' => $this->string()->notNull()->comment('Ссылка'),
            'hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Показан/Скрыт'),
	        'controller_id' => $this->string()->null()->comment('Контроллер'),
	        'action_id' => $this->string()->null()->comment('Действие'),
            'sort_order' => $this->integer()->unsigned()->notNull()->defaultValue(10)->comment('Порядок сортировки'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $this->addForeignKey('parent_menu', 'menu', 'parent_id', 'menu', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('menu');
    }
}
