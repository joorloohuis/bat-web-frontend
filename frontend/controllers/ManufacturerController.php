<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\db\Query;
use common\models\Manufacturer;

class ManufacturerController extends \yii\web\Controller
{

    // list fetch for typeahead widgets
    public function actionList() {
        echo Json::encode(array_map(
            function($m) {
                return $m['name'];
            },
            Manufacturer::find()->orderBy('name')->all()
        ));
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->can('listResources')) {
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
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('Manufacturer');
            if ($post['id']) {
                $model = Manufacturer::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Manufacturer #'.$post['id'].' updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update manufacturer #'.$post['id'].'.');
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
