<?php

use yii\db\Schema;
use yii\db\Migration;

class m150406_071636_extend_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'fullname', Schema::TYPE_STRING);
        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'fullname');
        return true;
    }

}
