<?php

use yii\db\Schema;
use yii\db\Migration;

class m150626_094216_add_auth_token extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'auth_token', Schema::TYPE_STRING);
        return true;
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'auth_token');
        return true;
    }

}
