<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\rest\ActiveController;
use api\models\Job;
use common\models\JobStatus;

class JobController extends ActiveController
{
    public $modelClass = 'api\models\Job';

    protected static $validParams = [
        'claim',
    ];

    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['view'], // overriden
            $actions['index'], // overridden
            $actions['delete'], // disabled
            $actions['create'], // disabled
            $actions['update'], // overridden
            $actions['options'] // disabled
        );
        return $actions;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (Yii::$app->user->can('api')) {
            $params = $this->parseQueryString();
            switch ($action) {
                case 'index':
                    if (isset($params['claim']) && !Yii::$app->user->identity->scanner_id) {
                        throw new ForbiddenHttpException('User has no scanner assigned.');
                    }
                    return true;
                case 'update':
                    if ($model->claimed_by != Yii::$app->user->identity->username) {
                        throw new ForbiddenHttpException('Job not claimed by this user.');
                    }
                    return true;
                default:
                    return true;
            }
        }
        throw new ForbiddenHttpException();
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }

    public function actionIndex()
    {
        $this->checkAccess('index');
        $params = $this->parseQueryString();
        if (isset($params['claim'])) {
            return Job::claim(Yii::$app->user->identity, $params['claim']);
        }
        return Job::getAccessibleJobs(Yii::$app->user->identity);
    }

    public function actionView($id)
    {
        $model = Job::findOne($id);
        $this->checkAccess('view', $model);
        return $model;
    }

    public function actionUpdate($id)
    {
        $model = Job::findOne($id);
        $this->checkAccess('update', $model);

        if (!$model->canUpdate()) {
            throw new BadRequestHttpException('Job not accepting updates.');
        }

        // TODO: validate input
        $update = Yii::$app->getRequest()->getBodyParams();
        if (!isset($update['status'])) {
           throw new BadRequestHttpException('Missing status parameter.');
        }
        switch ($update['status']) {
            case JobStatus::ACTIVE:
            case JobStatus::ERROR:
                $model->setCurrentStatus($update['status'], isset($update['full_status']) ? $update['full_status'] : '');
                break;
            case JobStatus::DONE:
                if (!array_key_exists('report', $update) || !array_key_exists('report_url', $update)) {
                    throw new BadRequestHttpException('Incomplete request.');
                }
                $model->setCurrentStatus($update['status']);
                $model->report = $update['report'];
                $model->report_url = $update['report_url'];
                $model->update();
                break;
            default:
                throw new BadRequestHttpException('Status not supported.');
        }
        return ['status' => $update['status']];
    }

    protected function parseQueryString()
    {
        $params = Yii::$app->request->queryParams;
        $filteredParams = [];
        foreach (static::$validParams as $param) {
            if (array_key_exists($param, $params)) {
                $filteredParams[$param] = $params[$param];
            }
        }
        return $filteredParams;
    }

    protected function prepareDataProvider()
    {
        // only select jobs for scanner assigned to this user
        $user = Yii::$app->get('user', false);
        $scannerId = $user && !$user->isGuest ? $user->identity->scanner_id : null;
        if (!$scannerId) {
           throw new BadRequestHttpException('API user not configured correctly.');
        }
        return new ActiveDataProvider([
            'query' => Job::find()->where(['scanner_id' => $scannerId]),
        ]);
    }

}
