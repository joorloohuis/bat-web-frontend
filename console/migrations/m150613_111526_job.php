<?php

use yii\db\Schema;
use yii\db\Migration;

class m150613_111526_job extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('job', [
            'id' => Schema::TYPE_PK,
            'firmware_id' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_STRING . ' NOT NULL',
            'report' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_by' => Schema::TYPE_STRING . ' NOT NULL',
            'updated_by' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        $this->createTable('job_status', [
            'id' => Schema::TYPE_PK,
            'job_id' => Schema::TYPE_INTEGER,
            'short_status' => Schema::TYPE_STRING . ' NOT NULL',
            'full_status' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_by' => Schema::TYPE_STRING . ' NOT NULL',
            'updated_by' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('job');
        $this->dropTable('job_status');
    }
}
