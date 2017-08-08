<?php

namespace Core;

/**
 * Class Config
 * @package Core
 * @author Pavel Spichak
 */
class Config
{

    protected static $settings = [];


    public static function get(string $key)
    {
        return (isset(self::$settings[$key])) ? self::$settings[$key] : null;
    }


    public static function set(string $key, $value)
    {
        self::$settings[$key] = $value;
    }
}