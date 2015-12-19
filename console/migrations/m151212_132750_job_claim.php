<?php

use yii\db\Schema;
use yii\db\Migration;

class m151212_132750_job_claim extends Migration
{
    public function up()
    {
        $this->addColumn('job', 'claim_id', Schema::TYPE_STRING);
        $this->createIndex('idx_claim_id', 'job', 'claim_id');
        return true;
    }

    public function down()
    {
        $this->dropIndex('idx_claim_id', 'job');
        $this->dropColumn('job', 'claim_id');
        return true;
    }
}
