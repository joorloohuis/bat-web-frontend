<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use common\models\Job;
use common\models\JobStatus;
use common\models\Firmware;

class JobController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['view', 'index', 'update', 'save', 'schedule', 'reset'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->can('listResources')) {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        // restriction for regular users is handled in the view
        return $this->render('index');
    }

    public function actionView()
    {
        if (Yii::$app->user->can('listResources')) {
            $model = Job::findOne(['id' => Yii::$app->request->get('id')]);
            if (Yii::$app->user->can('admin') || $model->created_by == Yii::$app->user->identity->username) {
                return $this->render('view', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('index');
    }

    public function actionCreate()
    {
        if (Yii::$app->user->can('createResource')) {
            $firmwareId = Yii::$app->request->get('firmware_id');
            if ($firmwareId) {
                $firmware = Firmware::findOne(['id' => $firmwareId]);
                $job = new Job();
                $job->firmware_id = $firmware->id;
                $job->insert();
                $job->setCurrentStatus(JobStatus::INIT);
                return $this->render('edit', [
                    'model' => $job,
                ]);
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->redirect(['index']);
    }

    public function actionUpdate()
    {
        if (!$model = Job::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such job.');
            return $this->render('index');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('Job');
            if ($post['id']) {
                $model = Job::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        if ($model->canSchedule()) {
                            $model->schedule();
                        }
                        Yii::$app->getSession()->setFlash('success', 'Job #'.$post['id'].' updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update job #'.$post['id'].'.');
                    }
                }
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->redirect(['index']);
    }

    public function actionDelete()
    {
        if (Yii::$app->user->can('deleteResource')) {
            $id = Yii::$app->request->get('id');
            if ($id) {
                $model = Job::findOne(['id' => $id]);
                if ($model->delete()) {
                    Yii::$app->getSession()->setFlash('success', 'Job deleted.');
                }
                else {
                    Yii::$app->getSession()->setFlash('error', 'Failed to delete job.');
                }
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->redirect(['index']);
    }

    public function actionSchedule()
    {
        if (Yii::$app->user->can('updateResource')) {
            $model = Job::findOne(['id' => Yii::$app->request->get('id')]);
            if ($model->schedule()) {
                Yii::$app->getSession()->setFlash('success', 'Job scheduled.');
            }
            else {
                Yii::$app->getSession()->setFlash('error', 'Failed to schedule job.');
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->redirect(['index']);
    }

    public function actionReset()
    {
        if (Yii::$app->user->can('updateResource')) {
            $model = Job::findOne(['id' => Yii::$app->request->get('id')]);
            if ($model->reset()) {
                Yii::$app->getSession()->setFlash('success', 'Job reset.');
            }
            else {
                Yii::$app->getSession()->setFlash('error', 'Failed to reset job.');
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->redirect(['index']);
    }
}
