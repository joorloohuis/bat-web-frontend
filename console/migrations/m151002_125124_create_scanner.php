<?php

use yii\db\Schema;
use yii\db\Migration;

class m151002_125124_create_scanner extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('scanner', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_by' => Schema::TYPE_STRING . ' NOT NULL',
            'updated_by' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        $this->addColumn('{{%user}}', 'scanner_id', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->createIndex('idx_scanner', 'user', 'scanner_id');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'scanner_id');
        $this->dropTable('scanner');

        return false;
    }

}
