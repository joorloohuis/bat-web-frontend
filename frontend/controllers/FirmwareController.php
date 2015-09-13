<?php

namespace frontend\controllers;

use Yii;
use yii\web\UploadedFile;
use common\models\Firmware;
use common\models\Upload;
use frontend\models\UploadForm;

class FirmwareController extends \yii\web\Controller
{

    public function actionIndex()
    {
        if (!Yii::$app->user->can('listResources')) {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        // restriction for regular users is handled in the view

        return $this->render('index', [
            'model' => new UploadForm(),
        ]);
    }

    public function actionUpload()
    {
        if (Yii::$app->user->can('createResource')) {
            $model = new UploadForm();
            if (Yii::$app->request->isPost) {
                $file = UploadedFile::getInstance($model, 'file');
                if ($file->getHasError()) {
                    Yii::$app->getSession()->setFlash('error', $file->error);
                    return $this->render('index', [
                        'model' => new UploadForm(),
                    ]);
                }
                $upload = new Upload();
                $upload->createFromUploadedFile($file);
                // create firmware
                // add upload to firmware

                return $this->render('index', [
                    'model' => new UploadForm(),
                ]);
                // return $this->render('edit', [
                //     'model' => $model,
                // ]);
            }
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
            return $this->render('index', [
                'model' => new UploadForm(),
            ]);
        }
    }

    public function actionUpdate()
    {
        if (Yii::$app->user->can('updateResource')) {
            if (!$model = Firmware::findOne(['id' => Yii::$app->request->get('id')])) {
                Yii::$app->getSession()->setFlash('error', 'No such firmware.');
                return $this->render('index');
            }
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('Firmware');
            if ($post['id']) {
                $model = Firmware::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Firmware updated.');
                    } else {
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

}
