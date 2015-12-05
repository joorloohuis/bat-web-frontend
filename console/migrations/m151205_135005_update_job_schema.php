<?php

use yii\db\Schema;
use yii\db\Migration;

class m151205_135005_update_job_schema extends Migration
{
    public function up()
    {
        $this->addColumn('job', 'claimed_by', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('job', 'claimed_at', Schema::TYPE_INTEGER . ' NOT NULL');
        return true;
    }

    public function down()
    {
        $this->dropColumn('job', 'claimed_at');
        $this->dropColumn('job', 'claimed_by');
        return true;
    }
}
