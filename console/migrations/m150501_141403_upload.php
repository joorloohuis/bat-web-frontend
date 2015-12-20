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
            'filename' => Schema::TYPE_STRING,
            'filesize' => Schema::TYPE_BIGINT,
            'checksum' => Schema::TYPE_STRING,
            'mimetype' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_STRING,
            'updated_by' => Schema::TYPE_STRING,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('upload');
    }
}
