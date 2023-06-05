<?php

namespace Core\File;

use Core\Exceptions\FileNotFoundException;

class File {
    private function __construct(){}

    /**
     * Get the absolute path of a file
     *
     * @param string $path
     * @return string
     */
    public static function full_path (string $path): string
    {
        $path = root_path() . ds() . trim($path, '/');
        $path = str_replace(['\\' , '/'] , ds() , $path);
        return $path;
    }

    /**
     * Check wheather a file exists or not
     *
     * @param string $path
     * @return boolean
     */
    public static function exists (string $path) :bool
    {
        return file_exists(static::full_path($path));
    }

    /**
     * Require a file
     *
     * @param string $path
     * @return mixed
     */
    public static function require_once (string $path) : mixed
    {
        if(static::exists($path)) return require_once(static::full_path($path));
        return throw new FileNotFoundException('This File ' . $path . ' doesn\'t exist');
    }

    /**
     * include a file
     *
     * @param string $path
     * @return mixed
     */
    public static function include_once (string $path) :mixed
    {
        if(static::exists($path)) return include_once(static::full_path($path));
        return throw new FileNotFoundException('This File ' . $path . ' doesn\'t exist');
    }

    /**
     * Require all files in specified directory
     *
     * @param string $path
     * @return void
     */
    public static function require_directory (string $path) : void
    {
        $files = array_diff(scandir(static::full_path($path)), ['.' , '..']);
        foreach($files as $file){
            $file_path = $path . ds() . $file;
            static::require_once($file_path);
        }
    }
}