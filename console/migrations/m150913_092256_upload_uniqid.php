<?php

use yii\db\Schema;
use yii\db\Migration;

class m150913_092256_upload_uniqid extends Migration
{
    public function up()
    {
        $this->addColumn('upload', 'uniqid', Schema::TYPE_STRING . ' NOT NULL');
        $this->createIndex('idx_uniqid', 'upload', 'uniqid', true);
        return true;
    }

    public function down()
    {
        $this->dropColumn('upload', 'uniqid');
        return true;
    }

}
