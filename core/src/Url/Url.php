<?php

namespace Core\Url;

use Core\Http\Request;

class Url
{
    private function __construct()
    {
    }

    /**
     * Get full URL of a given path
     *
     * @param string $path
     * @return string
     */
    public static function path(string $path): string
    {
        return Request::baseURL() . '/' . trim($path, '/');
    }

    public static function redirect(string $path)
    {
        header('location:' . Request::baseURL() . '/' . trim($path , '/'));
        exit;
    }
}
