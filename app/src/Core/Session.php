<?php

namespace Core;

/**
 * Class Session
 * @package Core
 * @author Pavel Spichak
 */
class Session
{


    public static function init()
    {
        session_start();
    }


    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }


    public static function get(string $key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : null;
    }


    public static function delete(string $key)
    {
        unset($_SESSION[$key]);
    }

}