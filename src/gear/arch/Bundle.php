<?php
//$MVC_LICENSE$

/*<namespace.current>*/
namespace gear\arch;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class Bundle
{
    static $locator;
    static $userRootDirectory;

    public static function setLocator($locator)
    {
        self::$locator = $locator;
    }

    public static function prob($module, $require = true, $once = true)
    {

    }

    public static function resolveUserModule($module, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $path = "$root\\$module";

        if ($require) {
            return $once
                ? require_once($path)
                : require($path);
        } else {
            return $once
                ? include_once($path)
                : include($path);
        }
    }

    public static function resolveAllUserModules($modules, $require = true, $once = true)
    {
        if (count($modules) == 0) return;
        $root = self::$userRootDirectory;

        foreach ($modules as $module) {
            $path = "$root\\$module.php";
            if ($require) {
                $result = $once
                    ? require_once($path)
                    : require($path);
            } else {
                $result = $once
                    ? include_once($path)
                    : include($path);
            }
        }
    }

    public static function resolveAllUserModuleFromDirectory($path, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $dI = new RecursiveDirectoryIterator("$root\\$path");
        if ($require) {
            if ($once) {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    require_once($file->getPathname());
                }
            } else {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    require($file->getPathname());
                }
            }
        } else {
            if ($once) {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    include_once($file->getPathname());
                }
            } else {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    include($file->getPathname());
                }
            }
        }
    }

    public static function Pal($module)
    {
        if (defined('Gear_IsPackaged')) return null;

        //TODO: improve algorithm.

        $phpVersion = phpversion();

        return require_once(__DIR__ . "\\gear\\arch\\pal\\general\\$module.php");
    }

    public static function Arch($module)
    {
        if (defined('Gear_IsPackaged')) return;
        //TODO: improve algorithm.

    }

    public static function setRootDirectory($userRootDirectory)
    {
        self::$userRootDirectory = $userRootDirectory;
    }
}

/*</module>*/
?>