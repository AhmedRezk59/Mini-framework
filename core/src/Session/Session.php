<?php

namespace Core\Session;

class Session
{
    private function __construct()
    {
    }

    /**
     * Start a session if not started
     *
     * @return void
     */
    public static function start(): void
    {
        if (!session_id()) {
            ini_set('session.use_only_cookies', 1);
            session_start();
        }
    }

    /**
     * Set a session key
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public static function set(string $key, mixed $value): mixed
    {
        $_SESSION[$key] = $value;
        return $value;
    }

    /**
     * Check wheather the session has a specific key
     *
     * @param string $key
     * @return boolean
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Get a specific session value by its key
     *
     * @param string $key
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        return static::has($key) ? $_SESSION[$key] : null;
    }

    /**
     * remove a specific key from session variable
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get the session variable
     *
     * @return array
     */
    public static function all(): array
    {
        return $_SESSION;
    }

    /**
     * Destroy the session
     *
     * @return void
     */
    public static function destroy() :void
    {
        foreach (static::all() as $key => $value) {
            static::remove($key);
        }
    }

    /**
     * Get a specific value by its key then delete it
     *
     * @param string $key
     * @return mixed
     */
    public static function flash (string $key) : mixed
    {
        $value = '' ;
        if(static::has($key)) {
            $value = static::get($key);
            static::remove($key);
        }
        return $value;
    }
}
