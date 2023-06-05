<?php

namespace Core\View;

use Core\Exceptions\FileNotFoundException;
use Core\File\File;

class View
{
    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Render a view
     *
     * @param string $path
     * @param array $data
     * @return mixed
     */
    public static function render(string $path, array $data = []): mixed
    {
        $path = 'views' . ds() . str_replace('.', ds(), $path) . '.mini.php';
        if(! File::exists($path)){
            throw new FileNotFoundException('This view "' . $path . '" doesn\'t exist');
        }
        ob_start();
        extract($data);
        require File::full_path($path);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
