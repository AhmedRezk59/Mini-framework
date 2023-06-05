<?php

namespace Core\Http;

class Http
{
    /**
     * CURL instance
     *
     * @var [type]
     */
    private static $curl;

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Initiating CURL
     *
     * @param string $url
     * @return static
     */
    public static function init(string $url): static
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        static::$curl = $curl;
        return new static();
    }

    /**
     * Adding headers to CURL instance
     *
     * @param array $headers
     * @return static
     */
    public static function withHeaders(array $headers): static
    {
        $curl = static::$curl;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        static::$curl = $curl;
        return new static();
    }

    /**
     * Retrieve data from the external api using GET method
     *
     * @return array
     */
    public static function get() :array
    {
        $curl = static::$curl;
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }

    /**
     * POST data to the external api using POST method
     *
     * @param array $data
     * @return array
     */
    public static function post(array $data) :array
    {
        $curl = static::$curl;
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }
}
