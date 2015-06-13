<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "upload".
 *
 * @property integer $id
 * @property string $filename
 * @property string $filesize
 * @property string $checksum
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class Upload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'filesize', 'mimetype', 'checksum', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'required'],
            [['filesize', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['filename', 'mimetype', 'checksum', 'created_by', 'updated_by'], 'string', 'max' => 255]
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
            'filename' => 'Filename',
            'filesize' => 'File Size',
            'mimetype' => 'MIME Type',
            'checksum' => 'Checksum',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    // internally set checksum and filesize, make sure file is in the right location
    protected function setFileProperties()
    {
        $path = $this->getContainerDir() . '/' . $this->filename;
        if (file_exists($path)) {
            $this->filesize = filesize($path);
            $oldChecksum = $this->checksum;
            $this->checksum = sha1_file($path);
            if ($oldChecksum != $this->checksum) {
                $this->createContainerDir();
                if (!rename($path, $this->getContainerDir() . '/' . $this->filename)) {
                    $this->checksum = $oldChecksum;
                }
            }
        }
    }

    public function save($runValidation = true, $attributeNames = NULL)
    {
        $this->setFileProperties();
        return parent::save($runValidation = true, $attributeNames = NULL);
    }

    public function update($runValidation = true, $attributeNames = NULL)
    {
        $this->setFileProperties();
        return parent::update($runValidation = true, $attributeNames = NULL);
    }

    public function delete()
    {
        $filename = $this->getContainerDir() . '/' . $this->filename;
        if (is_writable($filename) && unlink($filename)) {
            return parent::delete();
        }
        return false;
    }

    public function getContainerDir()
    {
        // reduce the number of files in a directory, use part of the checksum to create subdirs
        return Yii::$app->params['fileStorePath'] . '/' . substr($this->checksum, 0, 2);
    }

    public function createContainerDir()
    {
        return mkdir($this->getContainerDir(), 0755, true);
    }

}
