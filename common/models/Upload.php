<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

// FIXME: removing upload detaches it from firmware
// * use hard links to maintain separate names for the same data set?
// * disable delete for files that have attached firmwares

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
            [['filename'], 'required'],
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
            'checksum' => 'SHA256 Checksum',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    // find existing upload by file checksum
    public static function findByUploadedFile(UploadedFile $file)
    {
        $checksum = hash_file('sha256', $file->tempName);
        return static::findOne(['checksum' => $checksum]);
    }

    // create upload model from uploaded file, store in assigned location
    public static function createFromUploadedFile(UploadedFile $file)
    {
        $upload = new static();
        $upload->mimetype = FileHelper::getMimeType($file->tempName);
        $upload->checksum = hash_file('sha256', $file->tempName);
        $upload->filename = $file->getBaseName() . '.' . $file->getExtension();
        $upload->filesize = $file->size;

        $upload->createContainerDir();
        $file->SaveAs($upload->getContainerDir() . '/' . $upload->filename);
        $upload->save();
        return $upload;
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

    public function delete()
    {
        $filename = $this->getContainerDir() . '/' . $this->filename;
        if (is_writable($filename) && unlink($filename)) {
            // silently fail removing container directory if not empty
            rmdir($this->getContainerDir());
            return parent::delete();
        }
        return false;
    }

    public function getContainerDir()
    {
        // reduce the number of files in a directory, use the checksum to create subdirs
        return Yii::$app->params['fileStorePath'] . '/' . $this->checksum;
    }

    public function createContainerDir()
    {
        return FileHelper::createDirectory($this->getContainerDir(), 0755, true);
    }

    public function getDownloadUri()
    {
        return Yii::$app->params['fileStoreUri'] . '/' . $this->checksum . '/' . $this->filename;
    }
}
