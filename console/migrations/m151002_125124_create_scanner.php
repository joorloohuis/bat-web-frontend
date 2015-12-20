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
            'name' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_STRING,
            'updated_by' => Schema::TYPE_STRING,
        ], $tableOptions);
        $this->addColumn('{{%user}}', 'scanner_id', Schema::TYPE_INTEGER);
        $this->createIndex('idx_scanner', 'user', 'scanner_id');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'scanner_id');
        $this->dropTable('scanner');

        return true;
    }

}
