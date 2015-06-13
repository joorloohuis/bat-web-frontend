<?php

namespace frontend\controllers;

use Yii;
use common\models\ModelNumber;

class ModelNumberController extends \yii\web\Controller
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
        if (!$model = ModelNumber::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such model number.');
            return $this->render('index');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('ModelNumber');
            if ($post['id']) {
                $model = ModelNumber::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Model number #'.$post['id'].' updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update model number #'.$post['id'].'.');
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
