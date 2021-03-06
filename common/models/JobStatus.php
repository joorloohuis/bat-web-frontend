<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

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
 *
 * @property Job $job
 */
class JobStatus extends \yii\db\ActiveRecord
{
    const INIT    = 'init';
    const PENDING = 'pending';
    const CLAIMED = 'claimed';
    const ACTIVE  = 'active';
    const DONE    = 'done';
    const ERROR   = 'error';

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
            [['short_status'], 'required'],
            [['full_status'], 'string'],
            [['short_status', 'created_by', 'updated_by'], 'string', 'max' => 255]
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
            'job_id' => 'Job ID',
            'short_status' => 'Short Status',
            'full_status' => 'Full Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function __toString()
    {
        return $this->short_status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(Job::className(), ['id' => 'job_id']);
    }
}
