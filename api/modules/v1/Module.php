<?php

namespace app\modules\v1;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\components\RequestHeaderAuth;

class Module extends \yii\base\Module
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                RequestHeaderAuth::className(),
                QueryParamAuth::className(),
                // HttpBasicAuth::className(),
            ],
        ];
        return $behaviors;
    }

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
}
