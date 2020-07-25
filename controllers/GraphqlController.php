<?php


namespace app\controllers;


use app\components\AppContext;
use app\components\NodeLogger;
use app\components\QueryType;
use ErrorException;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use yii\rest\Controller;

class GraphqlController extends Controller
{
    public function actionIndex()
    {
        $_GET["_format"] = "json";

        //ini_set('display_errors', 0);

        $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE;
        if (!empty($_GET['debug'])) {
            set_error_handler(function ($severity, $message, $file, $line) use (&$phpErrors) {
                throw new ErrorException($message, 0, $severity, $file, $line);
            });
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
        }

        try {
            // Prepare context that will be available in all field resolvers (as 3rd argument):
            $appContext = new AppContext();
            $appContext->viewer = 1;
            $appContext->rootUrl = 'http://localhost:8080';
            $appContext->request = $_REQUEST;

            // Parse incoming query and variables
            if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $raw = file_get_contents('php://input') ?: '';
                $data = json_decode($raw, true) ?: [];
            } else {
                $data = $_REQUEST;
            }

            $data += ['query' => null, 'variables' => null];

            if (null === $data['query']) {
                $data['query'] = '{
  userController{
    index
  }
}';
            }

            // GraphQL schema to be passed to query executor:
            $schema = new Schema([
                'query' => new QueryType(),
                'typeLoader' => function ($name) {
                    NodeLogger::sendLog("typeloader");
                    NodeLogger::sendLog($name);
//                    return $name;
                    return QueryType::byTypeName($name);
                }
            ]);

            $result = GraphQL::executeQuery(
                $schema,
                $data['query'],
                null,
                $appContext,
                (array)$data['variables']
            );
            $output = $result->toArray($debug);
            $httpStatus = 200;
        } catch (\Exception $error) {
            $httpStatus = 500;
            $output['errors'] = [
                FormattedError::createFromException($error, $debug)
            ];
        }

        header('Content-Type: application/json', true, $httpStatus);
        return $output;
    }
}