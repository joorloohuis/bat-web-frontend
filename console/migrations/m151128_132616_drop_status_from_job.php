<?php

use yii\db\Schema;
use yii\db\Migration;

class m151128_132616_drop_status_from_job extends Migration
{
    public function up()
    {
        $this->dropColumn('job', 'status');
        return true;
    }

    public function down()
    {
        $this->addColumn('job', 'status', Schema::TYPE_STRING . ' NOT NULL AFTER scanner_id');
        return true;
    }
}
