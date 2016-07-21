<?php
//$MVC_LICENSE$

/*<requires>*/
//GearInvalidOperationException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
/*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class GearBundle
{
    static $locator;
    static $userRootDirectory;
    static $engineRootDirectory;

    public static function setLocator($locator)
    {
        self::$locator = $locator;
    }

    public static function prob($module, $require = true, $once = true)
    {

    }

    public static function resolvePackage($module)
    {
        $root = self::$engineRootDirectory;
        $path = "$root\\$module";
        require_once($path);
    }

    public static function resolveAllPackages($modules)
    {
        if (count($modules) == 0) return;
        $root = self::$engineRootDirectory;

        foreach ($modules as $module) {
            $path = "$root\\$module.php";
            require_once($path);
        }
    }

    public static function resolveAllPackageFromDirectory($path)
    {
        $root = self::$engineRootDirectory;
        $dI = new \RecursiveDirectoryIterator("$root\\$path");

        foreach (new \RecursiveIteratorIterator($dI) as $file) {
            $fileName = $file->getFilename();
            if ($fileName == '.' || $fileName == '..') continue;
            require_once($file->getPathname());
        }
    }

    public static function resolveUserModule($module, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $path = "$root\\$module";

        if ($require) {
            if (!file_exists($path)) {
                throw new GearInvalidOperationException('File not exists.');
            }
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
        $dI = new \RecursiveDirectoryIterator("$root\\$path");
        if ($require) {
            if ($once) {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
                    require_once($file->getPathname());
                }
            } else {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
                    require($file->getPathname());
                }
            }
        } else {
            if ($once) {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
                    include_once($file->getPathname());
                }
            } else {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
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

    public static function setEngineDirectory($engineRootDirectory)
    {
        self::$engineRootDirectory = $engineRootDirectory;
    }
}

/*</module>*/
?>