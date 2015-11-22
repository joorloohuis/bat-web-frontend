<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "job".
 *
 * @property integer $id
 * @property integer $firmware_id
 * @property string $status
 * @property string $report
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property Firmware $firmware
 * @property JobStatus[] $jobStatuses
 */
class Job extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'job';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firmware_id', 'created_at', 'updated_at'], 'integer'],
            [['report'], 'string'],
            [['status', 'created_by', 'updated_by'], 'string', 'max' => 255]
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
            'firmware_id' => 'Firmware ID',
            'status' => 'Status',
            'report' => 'Report',
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
    public function getFirmware()
    {
        return $this->hasOne(Firmware::className(), ['id' => 'firmware_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobStatuses()
    {
        return $this->hasMany(JobStatus::className(), ['job_id' => 'id']);
    }

    // TODO: don't delete jobs that are not pending or error
    public function delete()
    {
        return parent::delete();
    }

}
