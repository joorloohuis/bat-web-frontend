<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_141403_upload extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('upload', [
            'id' => Schema::TYPE_PK,
            'filename' => Schema::TYPE_STRING . ' NOT NULL',
            'filesize' => Schema::TYPE_BIGINT . ' NOT NULL',
            'checksum' => Schema::TYPE_STRING . ' NOT NULL',
            'mimetype' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_by' => Schema::TYPE_STRING . ' NOT NULL',
            'updated_by' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('upload');
    }
}
