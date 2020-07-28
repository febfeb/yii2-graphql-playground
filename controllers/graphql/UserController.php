<?php

namespace app\controllers\graphql;

use app\components\Controller;
use app\components\NodeLogger;
use app\components\QueryType;
use app\models\User;
use GraphQL\Type\Definition\Type;

class UserController extends Controller
{

    public function graphqlProps()
    {
        return [
            "index" => Type::string(),
            "user" => [
                "type" => QueryType::get(User::className()),
                "args" => [
                    "id" => [
                        "type" => QueryType::nonNull(Type::int()),
                    ],
                    "limit" => [
                        "type" => Type::int(),
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return "Selamat Datang di GraphQL Yii2";
    }

    public function actionUser($id, $limit = null)
    {
        NodeLogger::sendLog("LIMIT : ".$limit);
        return User::find()->where(["id" => $id])->one();
    }
}