<?php

namespace app\modules\v1\controllers;

use yii\rest\ActiveController;

class JobController extends ActiveController
{
    public $modelClass = 'common\models\Job';


    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['delete'],
            $actions['create']
        );
        return $actions;
    }

    public function actionIndex()
    {
        return ['patat' => 'friet'];
    }

}
