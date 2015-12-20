<?php

use yii\db\Schema;
use yii\db\Migration;

class m151204_103859_add_job_description extends Migration
{
    public function up()
    {
        $this->addColumn('job', 'description', Schema::TYPE_STRING);
        return true;
    }

    public function down()
    {
        $this->dropColumn('job', 'description');
        return true;
    }
}
