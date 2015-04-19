<?php
namespace frontend\models;

class User extends \common\models\User
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['fullname', 'filter', 'filter' => 'trim'],
                ['fullname', 'string', 'max' => 255],

                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ]
        );
    }

}
