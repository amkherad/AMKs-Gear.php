<?php

//spl_autoload_register(function ($class) {
//    GearBundle::prob("$class.php");
//});

class BundleLocator implements IGearBundleLocator
{
    function tryLocate($path, $require, $once)
    {
        if($path == 'R' || stripos($path, 'RedBean') >= 0) {
            require_once(__DIR__.'/../includes/redbean/rb.php');
            return true;
        }

        return false;
    }
}

?>