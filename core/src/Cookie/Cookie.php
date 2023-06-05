<?php

namespace Core\Cookie;

class Cookie
{
    private function __construct()
    {
    }

    /**
     * Set a cookie key
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public static function set(string $key, mixed $value): mixed
    {
        $time = time() + (30 * 60);
        setcookie($key , $value , $time ,'/', '' , false , true);
        return $value;
    }

    /**
     * Check wheather the cookie has a specific key
     *
     * @param string $key
     * @return boolean
     */
    public static function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Get a specific cookie value by its key
     *
     * @param string $key
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        return static::has($key) ? $_COOKIE[$key] : null;
    }

    /**
     * remove a specific key from cookie variable
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        unset($_COOKIE[$key]);
        setcookie($key , null , -1 , '/');
    }

    /**
     * Get the cookie variables
     *
     * @return array
     */
    public static function all(): array
    {
        return $_COOKIE;
    }

    /**
     * Destroy the cookie
     *
     * @return void
     */
    public static function destroy() :void
    {
        foreach (static::all() as $key => $value) {
            static::remove($key);
        }
    }
}
