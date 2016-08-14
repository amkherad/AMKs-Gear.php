<?php

namespace GearCms\data;

use \GearAppEngine;
use \GearInvalidOperationException;
use \R;

class DataInterface
{
    private static $isCalled = false;

    public static function open()
    {
        if (self::$isCalled) return;
        self::$isCalled = true;

        $config = GearAppEngine::$GearConfigCache;
        $driver = $config->getValue('dbDriver', Cms_Section_Config, 'mysql');
        $host = $config->getValue('dbHost', Cms_Section_Config, 'localhost');
        $database = $config->getValue('dbDatabasename', Cms_Section_Config, 'gearcms');
        $username = $config->getValue('dbUsername', Cms_Section_Config);
        $password = $config->getValue('dbPassword', Cms_Section_Config);
        if ($username == null || $password == null) {
            throw new GearInvalidOperationException();
        }
        $result = R::setup("$driver:host=$host;dbname=$database", $username, $password);
        R::setAutoResolve(TRUE);
        define('REDBEAN_MODEL_PREFIX', '\\GearCms\data\models\\');
        return $result;
    }
}