<?php

namespace Core\Http;

class Response {
    private function __construct(){}

    /**
     * Output data
     *
     * @param mixed $data
     * @return void
     */
    public static function output (mixed $data) :void
    {
        if(! $data) return ;
        if(! is_string($data)) {
            $data = static::json($data);
        };
        echo $data;
    }

    /**
     * Convert data to json
     *
     * @param $data
     * @return string|bool
     */
    private static function json ($data):string|bool
    {
        return json_encode($data);
    }
}