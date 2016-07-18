<?php
//$MVC_LICENSE$

/*<namespaces>*/
namespace gear\arch;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class Bundle
{
    static $locator;

    public static function setLocator($locator)
    {
        self::$locator = $locator;
    }

    public static function Pal($module)
    {
        if (defined('Gear_IsPackaged')) return null;

        //TODO: improve algorithm.

        $phpVersion = phpversion();

        return require_once(dirname(__FILE__) . "\\gear\\arch\\pal\\general\\$module.php");
    }

    public static function Arch($module)
    {
        if (defined('Gear_IsPackaged')) return;
        //TODO: improve algorithm.

    }
}
/*</module>*/
?>