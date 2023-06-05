<?php

use Core\Bootstrap\App;

class Application {
    private function __construct(){}

    /**
     * This method's purpose is to run the application
     *
     * @return void
     */
    public static function run () :void
    {
        App::run();
    }
}