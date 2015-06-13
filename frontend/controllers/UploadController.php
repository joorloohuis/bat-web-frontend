<?php

namespace frontend\controllers;

use Yii;
use common\models\Upload;

class UploadController extends \yii\web\Controller
{

    public function actionIndex()
    {
        if (!Yii::$app->user->can('listResources')) {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->render('index');
    }

    public function actionUpdate()
    {
        if (!$model = Upload::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such upload.');
            return $this->render('index');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('Upload');
            if ($post['id']) {
                $model = Upload::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Upload updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update upload.');
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
            if ($id = Yii::$app->request->get('id')) {
                $model = Upload::findOne(['id' => $id]);
                if ($model) {
                    if ($model->delete()) {
                        Yii::$app->getSession()->setFlash('success', 'Upload deleted.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to delete upload.');
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
