<?php

namespace app\components;

use app\controllers\graphql\UserController;
use Exception;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use ReflectionClass;
use Yii;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $fields = [
            'hello' => Type::string()
        ];

        //get all field inside model folders
        $modelClasses = $this->getClassInsideFolder(Yii::getAlias("@app/models"));
        foreach ($modelClasses as $class) {
            //Check if class has graphqlProps function
            if (method_exists($class, 'graphqlProps')) {
                //NodeLogger::sendLog($class." has graphqlProps func");
                //read function value
                //NodeLogger::sendLog($class::graphqlProps());

//                $fields[
            } else {

            }
        }

        $controllerClasses = $this->getClassInsideFolder(Yii::getAlias("@app/controllers/graphql"));
        foreach ($controllerClasses as $class) {
            $functions = get_class_methods($class);
            $reflect = new ReflectionClass($class);
            $className = $reflect->getShortName();
            $baseName = lcfirst($className);
            //NodeLogger::sendLog($baseName);
            $functionList = [];
            foreach ($functions as $function) {
                if (substr($function, 0, strlen("action")) == "action" && $function != "action") {
                    $functionList[] = $function;
                }
            }

            $fields[$baseName] = [
                'type' => self::get($class),
            ];
        }

//        NodeLogger::sendLog("OKE");

        $config = [
            'name' => 'Query',
            'fields' => $fields,
//                [
//                'user' => [
//                    'type' => Types::user(),
//                    'description' => 'Returns user by id (in range of 1-5)',
//                    'args' => [
//                        'id' => Types::nonNull(Types::id())
//                    ]
//                ],
//                'viewer' => [
//                    'type' => Types::user(),
//                    'description' => 'Represents currently logged-in user (for the sake of example - simply returns user with id == 1)'
//                ],
//                'stories' => [
//                    'type' => Types::listOf(Types::story()),
//                    'description' => 'Returns subset of stories posted for this blog',
//                    'args' => [
//                        'after' => [
//                            'type' => Types::id(),
//                            'description' => 'Fetch stories listed after the story with this ID'
//                        ],
//                        'limit' => [
//                            'type' => Types::int(),
//                            'description' => 'Number of stories to be returned',
//                            'defaultValue' => 10
//                        ]
//                    ]
//                ],
//                'lastStoryPosted' => [
//                    'type' => Types::story(),
//                    'description' => 'Returns last story posted for this blog'
//                ],
//                'deprecatedField' => [
//                    'type' => Types::string(),
//                    'deprecationReason' => 'This field is deprecated!'
//                ],
//                'fieldWithException' => [
//                    'type' => Types::string(),
//                    'resolve' => function() {
//                        throw new \Exception("Exception message thrown in field resolver");
//                    }
//                ],
//
//            ],
            'resolveField' => function ($rootValue, $args, $context, ResolveInfo $info) {
                NodeLogger::sendLog("==START==");
                NodeLogger::sendLog($rootValue);
                NodeLogger::sendLog($args);
                NodeLogger::sendLog("parentType:" . $info->parentType->name);
                foreach (self::$types as $key => $val) {
                    NodeLogger::sendLog("KEY:" . $key . " => " . $val->name);
                }
                NodeLogger::sendLog("==END===");


                return new UserController("user", Yii::$app->controller->module);

                $method = 'action' . ucfirst($info->fieldName);
                if (method_exists($rootValue, $method)) {
                    return $rootValue->{$method}($args, $context, $info);
                } else {
                    throw new Exception("Method " . $method . " not exist", 500);
                }

                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }

    private static $types = [];
    private static $classNames = [];

    public static function get($classname)
    {
        return static::byClassName($classname);
    }

    protected static function byClassName($classname)
    {

        $parts = explode("\\", $classname);
        $cacheName = strtolower(preg_replace('~Type$~', '', $parts[count($parts) - 1]));

        $type = null;

        if (!isset(self::$classNames[$cacheName])) {
            self::$classNames[$cacheName] = $classname;
        }

        if (!isset(self::$types[$cacheName])) {
            if (class_exists($classname)) {
                $type = self::buildType($classname);
            }

            self::$types[$cacheName] = $type;
        }

        $type = self::$types[$cacheName];

        if (!$type) {
            throw new Exception("Unknown graphql type: " . $classname);
        }
        return $type;
    }

    public static function byTypeName($shortName)
    {
        $cacheName = strtolower($shortName);
        $type = null;

        if (isset(self::$types[$cacheName])) {
            return self::$types[$cacheName];
        }

        $method = lcfirst($shortName);
        if (method_exists(get_called_class(), $method)) {
            $type = self::{$method}();
        }

        if (!$type) {
            throw new Exception("Unknown graphql type: " . $shortName);
        }
        return $type;
    }

    public static function getClassByName($name){
        $name = strtolower($name);
        return self::$classNames[$name];
    }

    private static function buildType($className)
    {
        NodeLogger::sendLog("ClassName: " . $className);
        $reflect = new ReflectionClass($className);
        $shortClassName = $reflect->getShortName();

        $fields = [
            'functionNotDefined' => Type::string()
        ];

        if (method_exists($className, 'graphqlProps')) {
            $fields = $className::graphqlProps();
        }

        //NodeLogger::sendLog("Short: " . $shortClassName);

        $config = [
            'name' => $shortClassName,
            'fields' => $fields,
            'resolveField' => function ($rootValue, $args, $context, ResolveInfo $info) {
//                NodeLogger::sendLog("==START/ACTION==");
//                NodeLogger::sendLog($args);
//                NodeLogger::sendLog("Parent: ".$info->parentType->name);
                $parentClassName = self::getClassByName($info->parentType->name);
//                NodeLogger::sendLog("==END/ACTION===");

                //TODO: Ubah ke mode
                $method = 'action' . ucfirst($info->fieldName);
                if (method_exists($rootValue, $method)) {
                    $obj = new $parentClassName();
                    return call_user_func_array([$obj, $method], $args);
//                    return $rootValue->{$method}($args, $context, $info);
                } else {
                    $getMethod = $info->fieldName;
                    return $rootValue->$getMethod;
                    //throw new Exception("Method ".$method." not exist", 500);
                }
            }
        ];
        return new ObjectType($config);
    }

    /**
     * This function only read given directory and should not recursive
     * @param $path
     * @return array
     */
    private function getClassInsideFolder($path)
    {
        $array = [];
        foreach (glob($path . '/*.php') as $file) {
            $content = file_get_contents($file);
            $tokens = token_get_all($content);

            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2; // Skip namespace keyword and whitespace
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2; // Skip class keyword and whitespace
                    $array[] = $namespace . '\\' . $tokens[$index][1];

                    # break if you have one class per file (psr-4 compliant)
                    # otherwise you'll need to handle class constants (Foo::class)
                    break;
                }
            }
        }

        return $array;
    }

    public function hello()
    {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }
}
