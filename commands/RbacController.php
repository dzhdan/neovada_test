<?php
namespace app\commands;


use Yii;
use yii\console\Controller;
use app\models\User;
use app\rbac\rules\user\Role;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $rule = new Role();
        $auth->add($rule);

        $user = $auth->createRole(User::ROLE_USER);
        $user->ruleName = $rule->name;
        $auth->add($user);

        $admin = $auth->createRole(User::ROLE_ADMIN);
        $admin->ruleName = $rule->name;
        $auth->add($admin);
    }

}