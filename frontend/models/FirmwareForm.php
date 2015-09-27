<?php

namespace frontend\models;

class FirmwareForm extends \yii\base\Model
{
    public $firmware_id;
    public $upload_name;
    public $checksum;
    public $manufacturer;
    public $model_number;
    public $device_type;
    public $odm;
    public $chipset;
    public $fcc_number;
    public $download_url;
    public $mac_address;
    public $notes;
    public $imagefile;

    public function rules()
    {
        return [
            [['manufacturer', 'model_number', 'device_type', 'odm', 'chipset', 'fcc_number', 'download_url', 'mac_address', 'notes'], 'string'],
            [['imagefile'], 'file'],
            [['manufacturer'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'upload_name' => 'File',
            'checksum' => 'SHA256 Checksum',
            'manufacturer' => 'Manufacturer',
            'odm' => 'ODM',
            'model_number' => 'Model Number',
            'device_type' => 'Device Type',
            'chipset' => 'Chipset',
            'fcc_number' => 'FCC Number',
            'download_url' => 'Download Url',
            'mac_address' => 'Mac Address',
            'notes' => 'Notes',
            'imagefile' => '',
        ];
    }

    public static function createFromFirmware($firmware)
    {
        $form = new static();
        $form->upload_name = $firmware->upload->filename;
        $form->checksum = $firmware->upload->checksum;
        $form->firmware_id = $firmware->id;
        $form->manufacturer = isset($firmware->manufacturer) ? $firmware->manufacturer->name : '';
        $form->odm = isset($firmware->odm) ? $firmware->odm->name : '';
        $form->model_number = isset($firmware->modelNumber) ? $firmware->modelNumber->value : '';
        $form->device_type = isset($firmware->deviceType) ? $firmware->deviceType->name : '';
        $form->chipset = isset($firmware->chipset) ? $firmware->chipset->value : '';
        $form->fcc_number = $firmware->fcc_number;
        $form->download_url = $firmware->download_url;
        $form->mac_address = $firmware->mac_address;
        $form->notes = $firmware->notes;

        return $form;
    }


}
