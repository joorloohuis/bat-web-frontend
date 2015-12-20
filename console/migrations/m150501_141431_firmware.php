<?php

use yii\db\Schema;
use yii\db\Migration;

class m150501_141431_firmware extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('firmware', [
            'id' => Schema::TYPE_PK,
            'upload_id' => Schema::TYPE_INTEGER,
            'manufacturer_id' => Schema::TYPE_INTEGER,
            'model_number_id' => Schema::TYPE_INTEGER,
            'device_type_id' => Schema::TYPE_INTEGER,
            'odm_id' => Schema::TYPE_INTEGER,
            'chipset_id' => Schema::TYPE_INTEGER,
            'fcc_number' => Schema::TYPE_STRING,
            'download_url' => Schema::TYPE_TEXT,
            'mac_address' => Schema::TYPE_STRING,
            'image_upload_id' => Schema::TYPE_INTEGER,
            'notes' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_STRING,
            'updated_by' => Schema::TYPE_STRING,
        ], $tableOptions);
        $this->addForeignKey('fk_firmware_upload', 'firmware', 'upload_id', 'upload', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_firmware_manufacturer', 'firmware', 'manufacturer_id', 'manufacturer', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_firmware_model_number', 'firmware', 'model_number_id', 'model_number', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_firmware_device_type', 'firmware', 'device_type_id', 'device_type', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_firmware_odm', 'firmware', 'odm_id', 'manufacturer', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_firmware_chipset', 'firmware', 'chipset_id', 'chipset', 'id', 'SET NULL', 'NO ACTION');
        $this->addForeignKey('fk_firmware_image_upload', 'firmware', 'image_upload_id', 'upload', 'id', 'SET NULL', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('firmware');
    }
}
