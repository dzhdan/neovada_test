<?php

namespace app\rbac\rules\user;

use app\models\User;
use Yii;
use yii\rbac\Rule;

class Role extends Rule
{
    public $name = 'userRole';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;

            if ($item->name === User::ROLE_USER) {
                return $role == User::ROLE_USER;
            } elseif ($item->name === User::ROLE_ADMIN) {
                return $role == User::ROLE_ADMIN;
            }
        }

        return false;
    }
}