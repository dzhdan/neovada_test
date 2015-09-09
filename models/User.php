<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

class User extends \app\models\base\User implements IdentityInterface
{

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $password;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        $rules = parent::rules();

        $rules[] = ['email', 'email'];
        $rules[] = ['email', 'unique'];
        $rules[] = ['password', 'required', 'on' => self::SCENARIO_CREATE];
        $rules[] = ['password', 'string'];

        return $rules;
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'deleted' => false]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    public function create()
    {
        $this->setPassword($this->password);
        $this->generateAuthKey();
        return $this->save();
    }

    public function updateUser()
    {
        $this->setPassword($this->password);
        return $this->save();
    }
}
