<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_113912_modelnumbers extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('model_number', [
            'id' => Schema::TYPE_PK,
            'value' => Schema::TYPE_STRING . ' NOT NULL',
            'manufacturer_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        $this->addForeignKey('fk_manufacturer', 'model_number', 'manufacturer_id', 'manufacturer', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('model_number');
    }
}
