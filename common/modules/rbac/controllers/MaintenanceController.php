<?php
namespace common\modules\rbac\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\User;

/**
  * Console RBAC access permission maintenance.
  *
  * NB: maintenance on the console is not subject to RBAC, and you have all permissions.
  */
class MaintenanceController extends Controller
{

    const LIST_FORMAT_HEADER = '| %8s | %-20s | %-20s | %7s |';
    const LIST_FORMAT_LINE   = '| %8d | %-20s | %-20s | %7s |';
    const LIST_FORMAT_SEP    = '+%10s+%22s+%22s+%9s+';

    /**
     * Alias for rbac/list
     */
    public function actionIndex()
    {
        return $this->actionList();
    }

    /**
      * List all users with RBAC information.
      */
    public function actionList()
    {
        $auth = Yii::$app->authManager;
        $users = User::find()
            ->orderBy('id')
            ->all();
        if (count($users)) {
            $separator = sprintf(self::LIST_FORMAT_SEP.PHP_EOL, str_repeat('-', 10), str_repeat('-', 22), str_repeat('-', 22), str_repeat('-', 9));
            echo $separator;
            printf(self::LIST_FORMAT_HEADER.PHP_EOL, 'id', 'username', 'role', 'status');
            echo $separator;
            foreach ($users as $user) {
                $roles = join(',', array_map(function($role) {return $role->name;}, $auth->getRolesByUser($user->id)));
                printf(self::LIST_FORMAT_LINE.PHP_EOL, $user->id, $user->username, $roles, $this->statusMap[$user->status]);
            }
            echo $separator;
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
      * List all available roles.
      */
    public function actionListRoles()
    {
        echo 'Available RBAC roles: '. implode(', ', array_keys($this->module->rbacModel)).PHP_EOL;
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
      * Initialize RBAC with roles and permissions.
      * Removes any existing RBAC data.
      */
    public function actionInit()
    {
        if ($this->buildRbacModel(true)) {
            $this->showSuccess('RBAC tables initialized.');
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Update the RBAC roles and permissions.
     */
    public function actionUpdate()
    {
        if ($this->buildRbacModel()) {
            $this->showSuccess('RBAC tables updated.');
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Assign a role to user.
     * @param int|string $id user id
     * @param string $role name of the role that should be assigned to the user
     *
     */
    public function actionAssign($uid, $roleName) {
        if (intval($uid)) {
            if (!$user = User::findOne($uid)) {
                $this->showError('No user with id ' . $uid . '.');
                return Controller::EXIT_CODE_ERROR;
            }
        }
        elseif (!$user = User::findByUsername($uid)) {
            $this->showError('No user with username ' . $uid . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $auth = Yii::$app->authManager;
        if (!$role = $auth->getRole($roleName)) {
            $this->showError('No role with name ' . $roleName . '.');
            return Controller::EXIT_CODE_ERROR;
        }

        $roles = $auth->getRolesByUser($user->id);
        if (!array_key_exists($roleName, $roles)) {
            $auth->assign($role, $user->id);
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Remove a role from a user user. If no role is specified, all roles will be removed.
     * @param int|string $id user id
     *
     */
    public function actionUnassign($uid, $roleName = null) {
        if (intval($uid)) {
            if (!$user = User::findOne($uid)) {
                $this->showError('No user with id ' . $uid . '.');
                return Controller::EXIT_CODE_ERROR;
            }
        }
        elseif (!$user = User::findByUsername($uid)) {
            $this->showError('No user with username ' . $uid . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $auth = Yii::$app->authManager;
        if ($roleName && !$role = $auth->getRole($roleName)) {
            $this->showError('No role with name ' . $roleName . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $roles = $auth->getRolesByUser($user->id);
        if ($roleName) {
            $auth->revoke($role, $user->id);
        }
        else {
            foreach ($roles as $revokeRole) {
                $auth->revoke($revokeRole, $user->id);
            }
        }
    }

    protected function buildRbacModel($reset = false) {
        $auth = Yii::$app->authManager;
        if ($reset) {
            if (strtolower($this->prompt('Clear existing model? [yes|no]: ') == 'yes')) {
                $auth->removeAll();
            }
            else {
                $this->showSuccess('RBAC tables not modified.');
                return false;
            }
        }
        if (!$adminRole = $auth->getRole($this->module->adminRole)) {
            $adminRole = $auth->createRole($this->module->adminRole);
            $auth->add($adminRole);
        }
        foreach ($this->module->rbacModel as $roleName => $permissions) {
            if (!$role = $auth->getRole($roleName)) {
                $role = $auth->createRole($roleName);
                $auth->add($role);
            }
            $existingPermissions = $auth->getPermissionsByRole($roleName);
            foreach ($permissions as $permissionName => $description) {
                if (!$permission = $auth->getPermission($permissionName)) {
                    $permission = $auth->createPermission($permissionName);
                    $auth->add($permission);
                }
                $permission->description = $description;
                $auth->update($permissionName, $permission);
                if (!$auth->hasChild($role, $permission)) {
                    $auth->addChild($role, $permission);
                }
                unset($existingPermissions[$permissionName]);
            }
            foreach ($existingPermissions as $permission) {
                $auth->removeChild($role, $permission);
            }
            if ($role != $adminRole && !$auth->hasChild($adminRole, $role)) {
                $auth->addChild($adminRole, $role);
            }
        }
        return true;
    }

    protected function showError($msg) {
        $this->stderr($msg . PHP_EOL, Console::FG_RED, Console::BOLD);
    }

    protected function showSuccess($msg) {
        $this->stdout($msg . PHP_EOL, Console::FG_GREEN, Console::BOLD);
    }

    protected $statusMap = [
        User::STATUS_ACTIVE  => 'active',
        User::STATUS_DELETED => 'deleted',
    ];


}
