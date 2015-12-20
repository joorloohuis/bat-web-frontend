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
            'status' => Schema::TYPE_STRING,
            'report' => Schema::TYPE_TEXT,
            'report_url' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_STRING,
            'updated_by' => Schema::TYPE_STRING,
        ], $tableOptions);
        $this->addForeignKey('fk_job_firmware', 'job', 'firmware_id', 'firmware', 'id', 'CASCADE', 'NO ACTION');
        $this->createTable('job_status', [
            'id' => Schema::TYPE_PK,
            'job_id' => Schema::TYPE_INTEGER,
            'short_status' => Schema::TYPE_STRING,
            'full_status' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_STRING,
            'updated_by' => Schema::TYPE_STRING,
        ], $tableOptions);
        $this->addForeignKey('fk_job_status_job', 'job_status', 'job_id', 'job', 'id', 'CASCADE', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('job_status');
        $this->dropTable('job');
    }
}
