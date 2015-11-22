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

    /**
     * claim and return one scanning task
     */
    public function actionClaim()
    {
        return [];
    }

    /**
     *
     */
    public function actionUpdate()
    {

    }

}
