<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "firmware".
 *
 * @property integer $id
 * @property integer $upload_id
 * @property integer $manufacturer_id
 * @property integer $model_number_id
 * @property integer $device_type_id
 * @property integer $odm_id
 * @property integer $chipset_id
 * @property string $fcc_number
 * @property string $download_url
 * @property string $mac_address
 * @property integer $image_upload_id
 * @property string $notes
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property Chipset $chipset
 * @property DeviceType $deviceType
 * @property Upload $imageUpload
 * @property Manufacturer $manufacturer
 * @property ModelNumber $modelNumber
 * @property Manufacturer $odm
 * @property Upload $upload
 */
class Firmware extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'firmware';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['upload_id', 'manufacturer_id', 'model_number_id', 'device_type_id', 'odm_id', 'chipset_id', 'image_upload_id', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'required'],
            [['download_url', 'notes'], 'string'],
            [['fcc_number', 'mac_address', 'created_by', 'updated_by'], 'string', 'max' => 255]
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'upload_id' => 'Upload',
            'manufacturer_id' => 'Manufacturer',
            'model_number_id' => 'Model Number',
            'device_type_id' => 'Device Type',
            'odm_id' => 'ODM',
            'chipset_id' => 'Chipset',
            'fcc_number' => 'Fcc Number',
            'download_url' => 'Download Url',
            'mac_address' => 'Mac Address',
            'image_upload_id' => 'Image Upload',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function findByUser($id)
    {
        return Firmware::find()
            ->where(['created_by' => $id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChipset()
    {
        return $this->hasOne(Chipset::className(), ['id' => 'chipset_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceType()
    {
        return $this->hasOne(DeviceType::className(), ['id' => 'device_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageUpload()
    {
        return $this->hasOne(Upload::className(), ['id' => 'image_upload_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelNumber()
    {
        return $this->hasOne(ModelNumber::className(), ['id' => 'model_number_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOdm()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'odm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpload()
    {
        return $this->hasOne(Upload::className(), ['id' => 'upload_id']);
    }
}
