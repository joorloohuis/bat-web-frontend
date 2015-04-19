<?php

namespace frontend\controllers;

use Yii;
use common\models\Manufacturer;

class ManufacturerController extends \yii\web\Controller
{

    public function actionIndex()
    {
        if (!Yii::$app->user->can('listManufacturers')) {
            Yii::$app->getSession()->setFlash('error', 'Not allowed.');
        }
        return $this->render('index');
    }

    public function actionUpdate()
    {
        if (!$model = Manufacturer::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such manufacturer.');
            return $this->render('index');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateManufacturer')) {
            $post = Yii::$app->request->post('Manufacturer');
            if ($post['id']) {
                $model = Manufacturer::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Manufacturer updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update manufacturer.');
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
