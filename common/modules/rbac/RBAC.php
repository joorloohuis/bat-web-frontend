<?php

namespace common\modules\rbac;

class RBAC extends \yii\base\Module
{
    /**
     * @var string $adminRole the name of the role that will be assigned all permissions
     *
     * The default is 'admin'.
     */
    public $adminRole = 'admin';

    /**
     * @var array $rbacModel an array with the RBAC model
     *
     * The RBAC model is specified as an array with the role names as keys and an array
     * of permission names and descriptions as values. For example:
     *
     */
    public $rbacModel;


    public function init()
    {
        parent::init();
        if (!isset($this->rbacModel[$this->adminRole])) {
            $this->rbacModel[$this->adminRole] = [];
        }
    }
}
