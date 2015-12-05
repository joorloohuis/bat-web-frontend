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
 * @property string $description
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
            [['download_url', 'notes'], 'string'],
            [['description', 'fcc_number', 'mac_address', 'created_by', 'updated_by'], 'string', 'max' => 255]
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'value' => function($event) {
                    $user = Yii::$app->get('user', false);
                    return $user && !$user->isGuest ? $user->identity->username : null;
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'upload_id' => 'File',
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

    public static function createFromUpload(Upload $upload)
    {
        $firmware = new static();
        $firmware->upload_id = $upload->id;
        $firmware->save();
        return $firmware;
    }

    public function updateFromFirmwareForm($form)
    {
        $this->description = $form['description'];
        if ($form['device_type']) {
            $device_type = DeviceType::findOrCreateByName($form['device_type']);
            $this->device_type_id = $device_type->id;
        }
        if ($form['manufacturer']) {
            $manufacturer = Manufacturer::findOrCreateByName($form['manufacturer']);
            $this->manufacturer_id = $manufacturer->id;
        }
        if ($form['odm']) {
            $odm = Manufacturer::findOrCreateByName($form['odm']);
            $this->odm_id = $odm->id;
        }
        if ($form['model_number']) {
            $model_number = ModelNumber::findOrCreateByValueAndManufacturer($form['model_number'], $this->manufacturer_id);
            $this->model_number_id = $model_number->id;
        }
        if ($form['chipset']) {
            $chipset = Chipset::findOrCreateByValue($form['chipset']);
            $this->chipset_id = $chipset->id;
        }
        $this->fcc_number = $form['fcc_number'];
        $this->download_url = $form['download_url'];
        $this->mac_address = $form['mac_address'];
        $this->notes = $form['notes'];
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
     *
     * @return \yii\db\ActiveQuery
     */
    public function findByUpload(Upload $upload)
    {
        return Firmware::find()
            ->where(['upload_id' => $upload->id]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function findByUserAndUpload($id, Upload $upload)
    {
        return Firmware::find()
            ->where(['created_by' => $id, 'upload_id' => $upload->id]);
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

    public function canDelete()
    {
        return true;
    }

    // TODO: check for existing scans
    public function delete()
    {
        if ($this->canDelete()) {
            return parent::delete();
        }
        return false;
    }

}
