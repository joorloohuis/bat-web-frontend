<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\User;

/**
  * Manages access permissions.
  *
  * NB: management on the console is not subject to RBAC, and you have all permissions.
  */
class RbacController extends Controller
{
    const LIST_FORMAT_HEADER = '%8s %-20s %-20s %7s';
    const LIST_FORMAT_LINE   = '%8d %-20s %-20s %7s';

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
            printf(self::LIST_FORMAT_HEADER.PHP_EOL, 'id', 'username', 'role', 'status');
            foreach ($users as $user) {
                $roles = join(',', array_map(function($role) {return $role->name;}, $auth->getRolesByUser($user->id)));
                printf(self::LIST_FORMAT_LINE.PHP_EOL, $user->id, $user->username, $roles, $this->statusMap[$user->status]);
            }
        }
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
     * Note that roles are exclusive, a user can only assume a single role.
     * @param int|string $id user id
     * @param string $role name of the role that should be assigned to the user
     *
     */
    public function actionAssign($uid, $roleName) {
        $user = User::findOne($uid);
        if (!$user) {
            $this->showError('No user with id ' . $uid . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $auth = Yii::$app->authManager;
        if (!$role = $auth->getRole($roleName)) {
            $this->showError('No role with name ' . $roleName . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $roles = $auth->getRolesByUser($uid);
        // revoke any roles the user has
        foreach ($roles as $revokeRole) {
            $auth->revoke($revokeRole, $uid);
        }
        $auth->assign($role, $uid);
        return Controller::EXIT_CODE_NORMAL;
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
        // omnipotent admin gets all permissions from other roles
        if (!$adminRole = $auth->getRole('admin')) {
            $adminRole = $auth->createRole('admin');
            $auth->add($adminRole);
        }
        foreach ($this->rbacModel as $roleName => $permissions) {
            if (!$role = $auth->getRole($roleName)) {
                $role = $auth->createRole($roleName);
                $auth->add($role);
            }
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

    protected $rbacModel = [
        'user' => [
            'listJobs' => 'List jobs for the current user',
            'addManufacturer' => 'Add manufacturers',
            'updateManufacturer' => 'Update manufacturers',
        ],
        'api' => [
            'listAllJobs' => 'List jobs for all users',
        ],
        'admin' => [ // NB: admin will inherit all permissions from other roles
            'listManufacturers' => 'List all manufacturers',
        ]
    ];

}
