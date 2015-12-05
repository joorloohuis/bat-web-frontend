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
 * @property integer $scanner_id
 * @property string $description
 * @property string $report
 * @property string $report_url
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $claimed_by
 * @property integer $claimed_at
 *
 * @property Firmware $firmware
 * @property Scanner $scanner
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
            [['scanner_id'], 'required'],
            [['firmware_id', 'scanner_id', 'created_at', 'updated_at', 'claimed_at'], 'integer'],
            [['report', 'report_url'], 'string'],
            [['description', 'created_by', 'updated_by', 'claimed_by'], 'string', 'max' => 255]
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
            'scanner_id' => 'Scanner ID',
            'description' => 'Description',
            'report' => 'Report',
            'report_url' => 'Report URL',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'claimed_by' => 'Claimed By',
            'claimed_at' => 'Claimed At',
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function findByUser($id)
    {
        return Job::find()
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
    public function getScanner()
    {
        return $this->hasOne(Scanner::className(), ['id' => 'scanner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatuses()
    {
        return $this->hasMany(JobStatus::className(), ['job_id' => 'id']);
    }

    public function canSchedule()
    {
        return in_array($this->getCurrentStatus(), [JobStatus::INIT]);
    }

    public function schedule()
    {
        if ($this->canSchedule()) {
            $this->setCurrentStatus(JobStatus::PENDING);
            return true;
        }
        return false;
    }

    public function canDelete()
    {
        return !in_array($this->getCurrentStatus(), [JobStatus::CLAIMED, JobStatus::ACTIVE]);
    }

    public function delete()
    {
        if ($this->canDelete()) {
            return parent::delete();
        }
        return false;
    }

    public function canReset()
    {
        return !in_array($this->getCurrentStatus(), [JobStatus::INIT, JobStatus::CLAIMED, JobStatus::ACTIVE]);
    }

    /**
     * Reset a job to initial state, so it won't be claimed and the history is cleared
     */
    public function reset()
    {
        if ($this->canReset()) {
            $this->report = null;
            $this->claimed_at = null;
            $this->claimed_by = null;
            JobStatus::deleteAll(['job_id' => $this->id]);
            $this->setCurrentStatus(JobStatus::INIT);
            return true;
        }
        return false;
    }

    /**
     * @return \app\model\JobStatus
     */
    public function getCurrentStatus()
    {
        return JobStatus::find()->where(['job_id' => $this->id])->orderBy('id DESC')->one();
    }

    public function setCurrentStatus($status, $full_status = '')
    {
        $current = new JobStatus;
        $current->job_id = $this->id;
        $current->short_status = $status;
        $current->full_status = $full_status;
        $current->insert();
    }

}
