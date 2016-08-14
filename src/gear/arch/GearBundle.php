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
use gear\arch\core\IGearBundleLocator;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearBundle
{
    static $locators = array();
    static $userRootDirectory;
    static $engineRootDirectory;

    /**
     * @param $locator IGearBundleLocator
     *
     * @throws GearInvalidOperationException
     */
    public static function registerLocator($locator)
    {
        if (!($locator instanceof IGearBundleLocator)) {
            throw new GearInvalidOperationException();
        }
        self::$locators[] = $locator;
    }

    /**
     * @param $module string
     * @param bool|true $require
     * @param bool|true $once
     * @return mixed
     */
    public static function prob($module, $require = true, $once = true)
    {
        $userRoot = self::$userRootDirectory;
        $engineRoot = self::$userRootDirectory;

        $firstBkSlash = stripos($module, '\\');
        if ($firstBkSlash > 0) {
            $noRoot = str_replace('\\', '/', substr($module, $firstBkSlash));

            $path = "$userRoot/$noRoot.php";
            if (!file_exists($path)) {
                $path = "$engineRoot/$noRoot.php";
                if (!file_exists($path)) {
                    $path = null;
                }
            }
            if ($path != null) {
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
        }

        $path = "$userRoot/$module.php";
        if (!file_exists($path)) {
            $path = "$engineRoot/$module.php";
            if (!file_exists($path)) {
                $path = null;
            }
        }
        if ($path != null) {
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

        foreach (self::$locators as $locator) {
            if ($locator->tryLocate($module, $require, $once)) {
                break;
            }
        }
    }

    public static function resolvePhar($phar)
    {

    }

    public static function resolvePackage($module)
    {
        $root = self::$userRootDirectory;
        $path = "$root/$module";
        if (file_exists("$path.php")) {
            require_once("$path.php");
        } elseif (file_exists("$path.phar")) {
            self::resolvePhar("$path.phar");
        } else {
            $root = self::$engineRootDirectory;
            $path = "$root/$module";

            if (file_exists("$path.php")) {
                require_once("$path.php");
            } elseif (file_exists("$path.phar")) {
                self::resolvePhar("$path.phar");
            } else {
                throw new GearInvalidOperationException("File '$module' not found.");
            }
        }
    }

    public static function resolveAllPackages($modules)
    {
        if (count($modules) == 0) return;
        $root = self::$engineRootDirectory;

        foreach ($modules as $module) {
            self::resolvePackage($module);
        }
    }

    public static function resolveAllPackageFromDirectory($path)
    {
        $root = self::$engineRootDirectory;
        $dI = new \RecursiveDirectoryIterator("$root/$path");

        foreach (new \RecursiveIteratorIterator($dI) as $file) {
            $fileName = $file->getFilename();
            if ($fileName == '.' || $fileName == '..') continue;
            self::resolvePackage($file->getPathname());
        }
    }

    public static function resolveUserModule($module, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $path = "$root/$module";

        if ($require) {
            if (!file_exists($path)) {
                throw new GearInvalidOperationException("File '$module' not found. path: '$path''");
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
            $path = "$root/$module.php";
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
        $dI = new \RecursiveDirectoryIterator("$root/$path");
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

        return require_once(__DIR__ . "/gear/arch/pal/general/$module.php");
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