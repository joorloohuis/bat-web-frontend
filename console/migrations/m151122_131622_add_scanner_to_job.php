<?php

use yii\db\Schema;
use yii\db\Migration;

class m151122_131622_add_scanner_to_job extends Migration
{
    public function up()
    {
        $this->addColumn('job', 'scanner_id', Schema::TYPE_INTEGER);
        $this->addForeignKey('fk_job_scanner', 'job', 'scanner_id', 'scanner', 'id', 'SET NULL', 'NO ACTION');
        return true;
    }

    public function down()
    {
        $this->dropForeignKey('fk_job_scanner', 'job');
        $this->dropColumn('job', 'scanner_id');
        return true;
    }
}
