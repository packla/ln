<?php

namespace app\models;


class AuthModel
{
    private static $login = 'admin';
    private static $password = 'g1Js3Bno';

    public static function checkAuth()
    {
        if (! isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER'])) {
            return false;
        }
        $login    = preg_replace('/[^a-zA-Z0-9]/i', '', $_SERVER['PHP_AUTH_USER']);
        $password = preg_replace('/[^a-zA-Z0-9]/i', '', $_SERVER['PHP_AUTH_PW']);

        return static::$login === $login && static::$password === $password;
    }
}