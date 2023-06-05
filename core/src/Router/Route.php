<?php

namespace Core\Router;

use BadFunctionCallException;
use BadMethodCallException;
use Core\Exceptions\ClassNotFoundException;
use Core\Exceptions\InterfaceNotImplementedException;
use Core\Exceptions\InvalidMethodCallException;
use Core\Exceptions\UnfoundRouteException;
use Core\Http\Request;
use Core\Interfaces\IMiddleware;

class Route
{
    /**
     * Routes container
     *
     * @var array
     */
    private static array $routes = [];

    /**
     * Middlewares
     *
     * @var string
     */
    private static string $middleware = '';

    /**
     * Routes prefix
     *
     * @var string
     */
    private static string $prefix = '';

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Add new route
     *
     * @param string $methods
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    private static function add(string $methods, string $uri, string|callable $callback): void
    {
        $uri = rtrim(static::$prefix . '/' . trim($uri, '/'), '/');
        $uri = $uri ?: '/';
        foreach (explode('|', $methods) as $method) {
            static::$routes[] = [
                'uri' => $uri,
                'callback' => $callback,
                'method' => $method,
                'middleware' => static::$middleware
            ];
        }
    }

    /**
     * Add new GET route
     *
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    public static function get(string $uri, string|callable $callback): void
    {
        static::add('GET', $uri, $callback);
    }

    /**
     * Add new POST route
     *
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    public static function post(string $uri, string|callable $callback): void
    {
        static::add('POST', $uri, $callback);
    }

    /**
     * Add new PUT route
     *
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    public static function put(string $uri, string|callable $callback): void
    {
        static::add('PUT', $uri, $callback);
    }
    /**
     * Add new PATCH route
     *
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    public static function patch(string $uri, string|callable $callback): void
    {
        static::add('PATCH', $uri, $callback);
    }

    /**
     * Add new DELETE route
     *
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    public static function delete(string $uri, string|callable $callback): void
    {
        static::add('DELETE', $uri, $callback);
    }

    /**
     * Add new route to all methods
     *
     * @param string $uri
     * @param string|callable $callback
     * @return void
     */
    public static function any(string $uri, string|callable $callback): void
    {
        static::add('GET|POST|PUT|PATCH|DELETE', $uri, $callback);
    }

    /**
     * Add prefix to a route
     *
     * @param string $prefix
     * @param object|callable $callback
     * @return void
     */
    public static function prefix(string $prefix, object|callable $callback): void
    {
        $parent_prefix = static::$prefix;
        static::$prefix .= '/' . trim($prefix, '/');
        if (is_callable($callback)) {
            call_user_func($callback);
        } else {
            throw new BadFunctionCallException('Invalid callback');
        }
        static::$prefix = $parent_prefix;
    }

    /**
     * Set a route middleware
     *
     * @param string $middleware
     * @param object|callable $callback
     * @return void
     */
    public static function middleware(string $middleware, object|callable $callback): void
    {
        $parent_middleware = static::$middleware;
        static::$middleware .= '|' . trim($middleware, '|');
        static::$middleware = trim(static::$middleware, '|');
        if (is_callable($callback)) {
            call_user_func($callback);
        } else {
            throw new BadFunctionCallException('Invalid callback');
        }
        static::$middleware = $parent_middleware;
    }

    /**
     * Handle The current route
     *
     */
    public static function handle()
    {
        $uri = Request::url();
        foreach (static::$routes as $route) {
            $matched = true;
            $route['uri'] = preg_replace('/{(.*)}/', '(.*)', $route['uri']);

            if (preg_match('#^' . $route['uri'] . '$#', $uri, $matches)) {
                array_shift($matches);
                $params = array_values($matches);
                foreach ($params as $p) {
                    if (strpos($p, '/') !== false) {
                        $matched = false;
                    }
                }
                if ($route['method'] !== Request::method()) {
                    throw new InvalidMethodCallException('This route doesn\'t support this method');
                }
                if ($matched == true) {
                    return static::invoke($route, $params);
                }
            } 
        }
        throw new UnfoundRouteException("This route doesn't exist");
    }

   /**
    * Invoke Route callback
    *
    * @param array $route
    * @param array $params
    * @return mixed
    */
    public static function invoke(array $route, array $params) :mixed
    {
        static::executeMiddleware($route);
        $callback = $route['callback'];
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        } 
        if( ! strpos($callback, '@') !== false) {
            throw new \InvalidArgumentException('Please provide a valid callback function');
        }
        list($controller, $method) = explode('@', $callback);
        $controller = 'App\Controllers\\' . $controller;
        if (! class_exists($controller)) {
            throw new ClassNotFoundException('This class doesn\'t exist');
        }
        $object = new $controller();
        if (! method_exists($object, $method)) {
            throw new BadMethodCallException('This method ' . $method . ' Doesn\'t exist in ' . $controller);
        }
        return call_user_func_array([$object, $method], $params);
    }

    /**
     * Execute the middlewares for the current route
     *
     * @param array $route
     * @return void
     */
    private static function executeMiddleware (array $route) :void
    {
        foreach(explode('|', $route['middleware']) as $middleware) {
            if($middleware !== ''){
                $middleware = 'App\Middlewares\\' . $middleware;
                if(! class_exists($middleware)){
                    throw new ClassNotFoundException('This middlware class ' . $middleware . ' doesn\'t exist');
                }
                $middleware = new $middleware();
                if(! $middleware instanceof IMiddleware){
                    throw new InterfaceNotImplementedException('The interface "IMiddleware" must be implemented by the mddleware');
                }
                call_user_func([$middleware , 'handle']);
            }
        }
    }
}
