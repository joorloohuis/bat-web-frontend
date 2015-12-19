<?php

namespace api\models;

use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;
use common\models\JobStatus;
use common\models\User;

class Job extends \common\models\Job
{
    const MAX_CLAIM_NUMBER = 10;

    public function fields()
    {
        $status = $this->getCurrentStatus();
        return [
            'id',
            'description',
            'status' => function($model) use ($status) {
                return $status->short_status;
            },
            'full_status' => function($model) use ($status) {
                return $status->full_status;
            },
            'report',
            'report_url',
            'firmware' => function($model) {
                $firmware = $model->firmware;
                return [
                    'name' => $firmware->description,
                    'upload' => $firmware->upload,
                ];
            },
        ];
    }

    public function canUpdate()
    {
        return in_array($this->getCurrentStatus(), [JobStatus::CLAIMED, JobStatus::ACTIVE]);
    }

    // return all jobs accessible to an API user
    // either unclaimed but for the scanner the user is associated with
    // or already claimed and possibly scanned by the user
    public static function getAccessibleJobs(User $identity)
    {
        return self::find()
            ->select('j.*')
            ->from('job j')
            ->innerJoin('job_status js1', 'j.id=js1.job_id')
            ->leftJoin('job_status js2', ['and', 'j.id=js2.job_id', 'js1.id<js2.id'])
            ->where([
                'js2.id' => null,
                'js1.short_status' => JobStatus::PENDING,
                'j.scanner_id' => $identity->scanner_id,
                'j.claim_id' => null
            ])
            ->orWhere([
                'js2.id' => null,
                'j.claimed_by' => $identity->username
            ])
            ->orderBy('js1.created_at')
            ->all();
    }

    // claim a number of jobs and return these
    public static function claim(User $identity, $count = 1)
    {
        $count = min(self::MAX_CLAIM_NUMBER, max(1, (int) $count));
        // claim all required jobs in a single query to avoid race conditions
        // the Yii2 query builder apparently doesn't support updates with joins on subqueries,
        // so we have to roll our own
        $updateSql =<<<SQL
            UPDATE job INNER JOIN (
                SELECT j.id FROM job j
                    INNER JOIN job_status js1 ON (j.id = js1.job_id)
                    LEFT OUTER JOIN job_status js2 ON (j.id = js2.job_id AND js1.id < js2.id)
                    WHERE js2.id IS NULL AND js1.short_status= :status AND j.claim_id IS NULL AND j.scanner_id = :scannerid
                    ORDER BY js1.created_at
                    LIMIT :count
            ) unclaimed ON job.id = unclaimed.id
            SET job.claim_id= :claimid, job.claimed_by= :username, job.claimed_at= :now
SQL;

        $claimId = uniqid(__METHOD__.':', true);
        $command = Yii::$app->db->createCommand($updateSql)
            ->bindValues([
                ':status' => JobStatus::PENDING,
                ':scannerid' => $identity->scanner_id,
                ':count' => $count,
                ':claimid' => $claimId,
                ':username' => $identity->username,
                ':now' => time(),
            ])
            ->execute();

        $claimedJobs = self::findAll(['claim_id' => $claimId]);
        foreach ($claimedJobs as $job) {
            $job->setCurrentStatus(JobStatus::CLAIMED);
        }
        return $claimedJobs;
    }

}
