<?php

namespace Core\Bootstrap;

use Core\File\File;
use Core\Http\Request;
use Core\Http\Response;
use Core\Router\Route;
use Core\Session\Session;

class App
{
    private function __construct()
    {
    }

    /**
     * Run the application and its services
     */
    public static function run()
    {
        Session::start();
        Request::handle();
        File::require_directory('routes');
        $data =  Route::handle();
        Response::output($data);
    }
}
