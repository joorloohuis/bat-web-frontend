<?php

namespace frontend\controllers;

use Yii;
use yii\web\UploadedFile;
use common\models\Firmware;
use common\models\Upload;
use frontend\models\UploadForm;
use frontend\models\FirmwareForm;

class FirmwareController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'upload', 'update', 'save', 'delete'],
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

    public function actionUpload()
    {
        if (Yii::$app->user->can('createResource')) {
            $form = new UploadForm();
            if (Yii::$app->request->isPost) {
                $file = UploadedFile::getInstance($form, 'file');
                if ($file->getHasError()) {
                    Yii::$app->getSession()->setFlash('error', $file->error);
                    return $this->render('index');
                }
                $upload = Upload::findByUploadedFile($file);
                // TODO: if upload resource already exists for this file, edit existing firmware or create new, take ownership into account
                if (!$upload) {
                    $upload = Upload::createFromUploadedFile($file);
                    if ($upload->hasErrors()) {
                        Yii::$app->getSession()->setFlash('error', $upload->getErrors());
                        return $this->render('index');
                    }
                }
                $firmware = Firmware::createFromUpload($upload);
                if ($firmware->hasErrors()) {
                    Yii::$app->getSession()->setFlash('error', $firmware->getErrors());
                    return $this->render('index');
                }

                return $this->render('edit', [
                    'model' => FirmwareForm::createFromFirmware($firmware),
                ]);
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
            return $this->render('index');
        }
    }

    public function actionUpdate()
    {
        if (Yii::$app->user->can('updateResource')) {
            if (!$firmware = Firmware::findOne(['id' => Yii::$app->request->get('id')])) {
                Yii::$app->getSession()->setFlash('error', 'No such firmware.');
                return $this->render('index');
            }
        }

        return $this->render('edit', [
            'model' => FirmwareForm::createFromFirmware($firmware),
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('FirmwareForm');
            if ($post['firmware_id']) {
                $model = Firmware::findOne(['id' => $post['firmware_id']]);
                $model->updateFromFirmwareForm($post);
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Firmware updated.');
                    }
                    else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update firmware.');
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
                $model = Firmware::findOne(['id' => $id]);
                if ($model->delete()) {
                    Yii::$app->getSession()->setFlash('success', 'Firmware deleted.');
                }
                else {
                    Yii::$app->getSession()->setFlash('error', 'Failed to delete firmware.');
                }
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->redirect(['index']);
    }

}
