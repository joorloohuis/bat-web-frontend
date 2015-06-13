<?php

namespace frontend\controllers;

use Yii;
use common\models\Firmware;

class FirmwareController extends \yii\web\Controller
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
        if (!$model = Firmware::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such firmware.');
            return $this->render('index');
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
