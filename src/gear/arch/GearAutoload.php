<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch;
    /*<namespace.current>*/
/*<namespace.use>*/
use gear\arch\GearBundle;
use gear\arch\core\GearInvalidOperationException;

/*</namespace.use>*/

/*<module>*/
class GearAutoload
{
    public static function register($type)
    {
        $function = null;
        switch (strtolower($type)) {
            case 'prob':
                spl_autoload_register(function ($className) {
                    self::_probing($className);
                });
                break;
            case 'userprobing':
                spl_autoload_register(function ($className) {
                    self::_userProbing($className);
                });
                break;
            default:
                throw new GearInvalidOperationException();
        }
    }

    private static function _probing($className)
    {
        GearBundle::prob($className);
    }

    private static function _userProbing($className)
    {
        GearBundle::resolveUserModule($className, true, true);
    }
}

/*</module>*/
?>