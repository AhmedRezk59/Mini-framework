<?php

/**
 * require the autoloader
 */

 require __DIR__ . '/../vendor/autoload.php';

/**
 * Bootstrap the application
*/

require __DIR__ . '/../bootstrap/app.php';

/**
 * Starting the application
 */

 Application::run();