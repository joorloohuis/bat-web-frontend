<?php

use yii\db\Schema;
use yii\db\Migration;

class m151122_133946_add_description_to_firmware extends Migration
{
    public function up()
    {
        $this->addColumn('firmware', 'description', Schema::TYPE_STRING);
        return true;
    }

    public function down()
    {
        $this->dropColumn('firmware', 'description');
        return true;
    }
}
