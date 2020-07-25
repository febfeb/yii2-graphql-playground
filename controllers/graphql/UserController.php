<?php
namespace app\controllers\graphql;

use app\components\QueryType;
use app\models\User;
use GraphQL\Type\Definition\Type;
use yii\web\Controller;

class UserController extends Controller
{

    public function graphqlProps(){
        return [
//            "id" => Type::id(),
            "index" => Type::string(),
            "user" => QueryType::get(User::className())
//            "users" => [User::className()]
        ];
    }

    public function actionIndex($args = null, $context = null, $info = null){
        return "JOSS MBAH";
    }

    public function actionUser($args = null, $context = null, $info = null){
        return User::find()->one();
    }
}