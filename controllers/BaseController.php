<?php


namespace app\controllers;


use GraphQL\Examples\Blog\Types;
use GraphQL\Type\Definition\ResolveInfo;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
//    public function __construct()
//    {
//        $config = [
//            'name' => 'BaseController',
//            'description' => 'Our blog authors',
//            'fields' => function() {
//                return [
//                    'id' => Types::id(),
//                ];
//            },
//            'interfaces' => [
//                Types::node()
//            ],
//            'resolveField' => function($user, $args, $context, ResolveInfo $info) {
//                $method = 'resolve' . ucfirst($info->fieldName);
//                if (method_exists($this, $method)) {
//                    return $this->{$method}($user, $args, $context, $info);
//                } else {
//                    return $user->{$info->fieldName};
//                }
//            }
//        ];
//        parent::__construct($config);
//    }


    public function beforeAction($action)
    {
        date_default_timezone_set("Asia/Jakarta");

        if(Yii::$app->user->isGuest){
            return $this->redirect(["site/login"]);
        }

        return parent::beforeAction($action);
    }
}