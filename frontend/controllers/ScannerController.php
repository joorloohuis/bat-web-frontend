<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\db\Query;
use common\models\Scanner;

class ScannerController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'update', 'save'],
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
        return $this->render('index');
    }

    public function actionCreate()
    {
        return $this->render('edit', [
            'model' => new Scanner,
        ]);
    }

    public function actionUpdate()
    {
        if (!$model = Scanner::findOne(['id' => Yii::$app->request->get('id')])) {
            Yii::$app->getSession()->setFlash('error', 'No such Scanner.');
            return $this->render('index');
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        if (Yii::$app->user->can('updateResource')) {
            $post = Yii::$app->request->post('Scanner');
            if ($post['id']) {
                $model = Scanner::findOne(['id' => $post['id']]);
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->update()) {
                        Yii::$app->getSession()->setFlash('success', 'Scanner #'.$post['id'].' updated.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to update Scanner #'.$post['id'].'.');
                    }
                }
            }
            else {
                $model = new Scanner;
                $model->attributes = $post;
                if ($model->validate()) {
                    if ($model->insert()) {
                        Yii::$app->getSession()->setFlash('success', 'Scanner created.');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to create scanner.');
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
