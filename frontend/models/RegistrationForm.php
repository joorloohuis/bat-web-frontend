<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Registration form
 */
class RegistrationForm extends Model
{
    public $username;
    public $fullname;
    public $email;
    public $password;
    public $repeatpassword;

    public function attributeLabels()
    {
        return [
            'username' => 'User name',
            'fullname' => 'Full name',
            'email' => 'Email address',
            'password' => 'Password',
            'repeatpassword' => 'Repeat password',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['fullname', 'filter', 'filter' => 'trim'],
            ['fullname', 'string', 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['repeatpassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->fullname = $this->fullname;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
