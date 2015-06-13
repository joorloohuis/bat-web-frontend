<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "job_status".
 *
 * @property integer $id
 * @property integer $job_id
 * @property string $short_status
 * @property string $full_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class JobStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'job_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'created_at', 'updated_at'], 'integer'],
            [['short_status', 'full_status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'required'],
            [['full_status'], 'string'],
            [['short_status', 'created_by', 'updated_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'short_status' => 'Short Status',
            'full_status' => 'Full Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
