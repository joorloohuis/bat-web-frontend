<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\User;

/**
  * Manages users.
  *
  * NB: management on the console is not subject to RBAC, and you have all permissions.
  */
class UserController extends Controller
{
    const LIST_FORMAT_HEADER = '| %8s | %-20s | %7s | %-30s | %-40s |';
    const LIST_FORMAT_LINE   = '| %8d | %-20s | %7s | %-30s | %-40s |';
    const LIST_FORMAT_SEP    = '+%10s+%22s+%9s+%32s+%42s+';

    /**
     * Alias for user/list
     */
    public function actionIndex()
    {
        return $this->actionList();
    }

    /**
      * List all user information.
      */
    public function actionList()
    {
        $users = User::find()
            ->orderBy('id')
            ->all();
        if (count($users)) {
            $separator = sprintf(self::LIST_FORMAT_SEP.PHP_EOL, str_repeat('-', 10), str_repeat('-', 22), str_repeat('-', 9), str_repeat('-', 32), str_repeat('-', 42));
            echo $separator;
            printf(self::LIST_FORMAT_HEADER.PHP_EOL, 'id', 'username', 'status', 'fullname', 'email');
            echo $separator;
            foreach ($users as $user) {
                printf(self::LIST_FORMAT_LINE.PHP_EOL, $user->id, $user->username, $this->statusMap[$user->status], $user->fullname, $user->email);
            }
            echo $separator;
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Add a user.
     * @param string $username login name
     * @param string $password password
     * @param string $fullname human readable name
     * @param string $email email address
     */
    public function actionAdd($username, $password, $fullname = '', $email = '') {
        $user = User::findOne(['username' => $username]);
        if ($user) {
            $this->showError('A user with login name ' . $username . ' already exists.');
            return Controller::EXIT_CODE_ERROR;
        }
        $user = new User();
        $user->username = $username;
        $user->fullname = $fullname;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        if ($user->save()) {
            return Controller::EXIT_CODE_NORMAL;
        }
        return Controller::EXIT_CODE_ERROR;
    }

    /**
     * Reset a password.
     * @param string $username login name
     * @param string $password password
     */
    public function actionPassword($username, $password) {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            $this->showError('No user with login name ' . $username . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $user->setPassword($password);
        $user->generateAuthKey();
        if ($user->save()) {
            return Controller::EXIT_CODE_NORMAL;
        }
        return Controller::EXIT_CODE_ERROR;
    }

    /**
     * Disable a user.
     * @param string $username login name
     */
    public function actionDisable($username) {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            $this->showError('No user with login name ' . $username . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $user->status = User::STATUS_DELETED;
        if ($user->save()) {
            return Controller::EXIT_CODE_NORMAL;
        }
        return Controller::EXIT_CODE_ERROR;
    }

    /**
     * Enable a user.
     * @param string $username login name
     */
    public function actionEnable($username) {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            $this->showError('No user with login name ' . $username . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $user->status = User::STATUS_ACTIVE;
        if ($user->save()) {
            return Controller::EXIT_CODE_NORMAL;
        }
        return Controller::EXIT_CODE_ERROR;
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
