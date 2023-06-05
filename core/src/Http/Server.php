<?php

namespace Core\Http;

class Server {
    private function __construct(){}

    /**
     * Get all server variables
     *
     * @return array
     */
    public static function all() :array
    {
        return $_SERVER;
    }

    /**
     * Check if Server variable has a key
     *
     * @param string $key
     * @return boolean
     */
    public static function has ($key):bool
    {
        return isset($_SERVER[$key]);
    }

    /**
     * Get a specific key from Server variable
     *
     * @param string $key
     * @return string|null
     */
    public static function get (string $key) :?string
    {
        return static::has($key) ? $_SERVER[$key] : null;
    }

    public static function pathInfo ($path) :array
    {
        return pathinfo($path);
    }
}