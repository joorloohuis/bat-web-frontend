<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use common\models\Chipset;

class ChipsetController extends \yii\web\Controller
{

    // list fetch for typeahead widgets
    public function actionList() {
        echo Json::encode(array_map(
            function($m) {
                return $m['value'];
            },
            Chipset::find()->orderBy('value')->all()
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
        if (!$model = Chipset::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such chipset.');
            return $this->render('index');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('Chipset');
            if ($post['id']) {
                $model = Chipset::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Chipset #'.$post['id'].' updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update chipset #'.$post['id'].'.');
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
