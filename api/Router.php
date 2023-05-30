<?php

require_once __DIR__ . '/controllers/ServiceController.php';
require_once __DIR__ . '/controllers/RequestController.php';
require_once __DIR__ . '/controllers/PostController.php';
require_once __DIR__ . '/controllers/EventController.php';
require_once __DIR__ . '/controllers/UserController.php';

class Router {
    private static $routes = [];

    public static function route($method, $uri) {
        $found = false;

        //Check for URI
        foreach(self::$routes as $uriPattern => $values) {
            if(!preg_match_all($uriPattern, $uri))
                continue;
            
            //Check if method is supported
            if(array_key_exists($method, $values)) {
                $found = true;
                $query = parse_url($uri, PHP_URL_QUERY);
                
                if(isset($query))
                    parse_str($query, $queries);
                
                echo json_encode(call_user_func([$values[$method]['class'], $values[$method]['handlerMethod']], $queries ?? null));
            } else
                exitError(405, 'Method not allowed for this URI');
        }

        //URI not found
        if(!$found)
            exitError(404, 'URI not found');
    }

    public static function addBaseController($controllerClass, $baseName) {
        try {
            self::addGenericController($controllerClass, $baseName);
            self::addGET("/^\/api\/$baseName\/user\/\d+(\?limit=\d+?&offset=\d+)?$/", $controllerClass, 'getAllBy');
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    public static function addGenericController($controllerClass, $baseName) {
        try {
            self::addReadOnlyController($controllerClass, $baseName);

            self::addPOST("/^\/api\/$baseName(\?limit=\d+?&offset=\d+)?$/", $controllerClass, 'create');
            self::addPATCH("/^\/api\/$baseName\/\d+$/", $controllerClass, 'update');
            self::addDELETE("/^\/api\/$baseName\/\d+$/", $controllerClass, 'delete');
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    public static function addReadOnlyController($controllerClass, $baseName) {
        try {
            self::addGET("/^\/api\/$baseName(\?limit=\d+?&offset=\d+)?$/", $controllerClass, 'getAll');
            self::addGET("/^\/api\/$baseName\/\d+$/", $controllerClass, 'get');
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    public static function addGET($uri, $className, $handlerMethod) {
        try {
            self::addRoute('GET', $uri, $className, $handlerMethod);
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    public static function addPOST($uri, $className, $handlerMethod) {
        try {
            self::addRoute('POST', $uri, $className, $handlerMethod);
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    public static function addPATCH($uri, $className, $handlerMethod) {
        try {
            self::addRoute('PATCH', $uri, $className, $handlerMethod);
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    public static function addDELETE($uri, $className, $handlerMethod) {
        try {
            self::addRoute('DELETE', $uri, $className, $handlerMethod);
        } catch(\InvalidArgumentException $ex) {
            throw $ex;
        }
    }

    private static function addRoute($method, $uri, $className, $handlerMethod) {
        if(!method_exists($className, $handlerMethod))
            throw new InvalidArgumentException("Handler method does not exist in $className class");

        if(!is_callable([$className, $handlerMethod]))
            throw new InvalidArgumentException('Handler method is not callable');
            
        self::$routes[$uri][$method] = [
            'class' => $className,
            'handlerMethod' => $handlerMethod
        ];
    }
}

Router::addBaseController('ServiceController', 'services');
Router::addBaseController('RequestController', 'requests');
Router::addBaseController('PostController', 'posts');
Router::addBaseController('EventController', 'events');
Router::addGenericController('UserController', 'users');

Router::addGET('/^\/api\/requests\/user\/\d+\/incoming(\?limit=\d+?&offset=\d+)?$/', 'RequestController', 'getAllUndeclinedFor');
Router::addGET('/^\/api\/events\/user\/\d+\?month=\d+&year=\d+$/', 'EventController', 'getMonthlyAllBy');
Router::addGET('/^\/api\/users\?type=(user|servicer)(&limit=\d+?&offset=\d+)?$/', 'UserController', 'getAllByType');