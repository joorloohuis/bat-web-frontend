<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\User;

/**
  * Manages scanner API users.
  *
  * NB: management on the console is not subject to RBAC, and you have all permissions.
  */
class ScannerController extends Controller
{
    const LIST_FORMAT_HEADER = '| %8s | %-20s | %7s | %-30s | %10s |';
    const LIST_FORMAT_LINE   = '| %8d | %-20s | %7s | %-30s | %10d |';
    const LIST_FORMAT_SEP    = '+%10s+%22s+%9s+%32s+%12s+';

    /**
     * Alias for scanner/list
     */
    public function actionIndex()
    {
        return $this->actionList();
    }

    /**
      * List scanner API user information.
      */
    public function actionList()
    {
        $users = User::find()
            ->where(['not', ['scanner_id' => null]])
            ->orderBy('id')
            ->all();
        if (count($users)) {
            $separator = sprintf(self::LIST_FORMAT_SEP.PHP_EOL, str_repeat('-', 10), str_repeat('-', 22), str_repeat('-', 9), str_repeat('-', 32), str_repeat('-', 12));
            echo $separator;
            printf(self::LIST_FORMAT_HEADER.PHP_EOL, 'id', 'username', 'status', 'access token', 'scanner id');
            echo $separator;
            foreach ($users as $user) {
                printf(self::LIST_FORMAT_LINE.PHP_EOL, $user->id, $user->username, $this->statusMap[$user->status], $user->auth_token, $user->scanner_id);
            }
            echo $separator;
        }
        else {
            $this->showError('No scanner API users found');
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Set a scanner id.
     * @param string $username login name
     * @param integer $scanner_id scanner id
     */
    public function actionSetId($username, $scanner_id) {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            $this->showError('No user with login name ' . $username . '.');
            return Controller::EXIT_CODE_ERROR;
        }
        $user->scanner_id = $scanner_id;
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
