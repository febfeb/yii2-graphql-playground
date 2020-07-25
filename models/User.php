<?php

namespace app\models;

use app\components\NodeLogger;
use app\components\QueryType;
use GraphQL\Type\Definition\Type;
use yii\web\IdentityInterface;

class User extends \app\models\base\User implements IdentityInterface
{
    public $authKey;
    public $accessToken;

    public function graphqlProps(){
        return [
            "id" => Type::id(),
            "name" => Type::string(),
            "username" => Type::string(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return User::find()->where(["id" => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User
     */
    public static function findByUsername($username)
    {
        NodeLogger::sendLog("Find By Username ".$username);
        return User::find()->where(["username" => $username])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        NodeLogger::sendLog($this->password . " vs " . $password);
        return $this->password === md5($password);
    }
}
