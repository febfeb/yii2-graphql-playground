<?php

namespace app\models;

use app\models\base\Role as BaseRole;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "role".
 */
class Role extends BaseRole
{
    const SUPER_ADMINISTRATOR = 1;

    public function graphqlProps(){
        return [
            "id" => "int",
            "name" => "string",
            "users" => [User::className()]
        ];
    }
}
