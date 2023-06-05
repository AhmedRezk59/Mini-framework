<?php

use Core\View\View;

if (!function_exists('root_path')) {
    function root_path()
    {
        return realpath(__DIR__ . '/../');
    }
}

if (!function_exists('ds')) {
    function ds()
    {
        return DIRECTORY_SEPARATOR;
    }
}

if (!function_exists('dump')) {
    function dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

if (!function_exists('view')) {
    function view(string $path , array $data)
    {
        return View::render($path , $data);
    }
}
