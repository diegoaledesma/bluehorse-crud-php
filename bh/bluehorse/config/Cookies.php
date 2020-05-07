<?php

class Cookies
{
    public function create($value = '')
    {
        setcookie('__BH__', $value, strtotime('+1 year'), '/');
    }

    public function destroy()
    {
        setcookie('__BH__', '', strtotime('-1 year'), '/');
    }

    public function check()
    {
        return isset($_COOKIE['__BH__']) && ! empty($_COOKIE['__BH__']) ? true : false;
    }

    public function explodeCookie()
    {
        return explode('.', $_COOKIE['__BH__']);
    }

    public function getId()
    {
        return self::explodeCookie()[0];
    }

    public function getToken()
    {
        return join('.', self::explodeCookie());
    }
}