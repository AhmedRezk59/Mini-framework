<?php

namespace Core\Http;

class Request
{
    /**
     * Base URL
     *
     * @var string
     */
    private static string $base_url;

    /**
     * URL
     *
     * @var string
     */
    private static string $url;

    /**
     * Full URL
     *
     * @var string
     */
    private static string $full_url;

    /**
     * Query String
     *
     * @var string
     */
    private static string $query_string;

    /**
     * Script name
     *
     * @var string
     */
    private static string $script_name;

    private function __construct()
    {
    }

    public static function handle(): void
    {
        static::$script_name = str_replace('\\', '', dirname(Server::get('SCRIPT_NAME')));
        static::setBaseURL();
        static::setURL();
    }

    /**
     * Set base URL
     *
     * @return void
     */
    private static function setBaseURL(): void
    {
        $protocol = Server::get('REQUEST_SCHEME') . '://';
        $host = Server::get('HTTP_HOST');
        $script_name = static::$script_name;
        static::$base_url = $protocol . $host . $script_name;
    }

    /**
     * Set URL
     *
     * @return void
     */
    private static function setURL(): void
    {
        $request_uri = urldecode(Server::get('REQUEST_URI'));
        $request_uri = rtrim(preg_replace("#^" . static::$script_name . "#i", '', $request_uri), '/');
        static::$full_url = $request_uri;
        $query_string = '';
        if (strpos($request_uri, '?') !== false) {
            list($request_uri, $query_string) = explode('?', $request_uri);
        }
        static::$url = $request_uri ?: '/';
        static::$query_string = $query_string;
    }

    /**
     * Get base URL
     *
     * @return string
     */
    public static function baseURL(): string
    {
        return static::$base_url;
    }

    /**
     * GEt URL
     *
     * @return string
     */
    public static function url(): string
    {
        return static::$url;
    }

    /**
     * Get Query String
     *
     * @return string
     */
    public static function query_string(): string
    {
        return static::$query_string;
    }

    /**
     * Get Full URL
     *
     * @return string
     */
    public static function full_url(): string
    {
        return static::$full_url;
    }

    /**
     * GET HTTP METHOD
     *
     * @return string
     */
    public static function method(): string
    {
        return Server::get('REQUEST_METHOD');
    }

    /**
     * Check if the Request has a specific value
     *
     * @param array $type
     * @param string $key
     * @return boolean
     */
    public static function has (array $type , string $key) : bool
    {
        return array_key_exists($key , $type);
    }

    /**
     * Get the value of specific key
     *
     * @param string $key
     * @param array|null $type
     * @return string|null
     */
    public static function value(string $key , ?array $type = null): ?string
    {
        $type = isset($type) ? $type : $_REQUEST;
        return static::has($type, $key) ? $type[$key] : null;
    }

    /**
     * Get value from GET request
     *
     * @param string $key
     * @return string
     */
    public static function get(string $key): string
    {
        return static::value($key , $_GET);
    }

    /**
     * Get value from POST request
     *
     * @param string $key
     * @return string
     */
    public static function post(string $key): string
    {
        return static::value($key, $_POST);
    }

    /**
     * Set the value of specific key
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public static function set (string $key , mixed $value) : mixed
    {
        $_REQUEST[$key] = $value;
        $_GET[$key] = $value;
        $_POST[$key] = $value;

        return $value;
    }
    
    /**
     * Get all request keys and values
     *
     * @return array
     */
    public static function all () : array 
    {
        return $_REQUEST;
    }
}
