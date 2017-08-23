<?php

use yii\db\Migration;

class m170822_130648_provider extends Migration
{
    public function up()
    {
        $this->createTable('provider', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'name' => $this->string()->null()->comment('ФИО/Название компании'),
            'deleted'=> $this->boolean()->defaultValue(0)->comment('Статус поставщика (удалён/не удалён)'),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('provider');
    }

}
