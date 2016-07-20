<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch;
    /*<namespace.current>*/
/*<namespace.use>*/
use gear\arch\Bundle;
use gear\arch\core\InvalidOperationException;

/*</namespace.use>*/

/*<module>*/

class Autoload
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
                throw new InvalidOperationException();
        }
    }

    private static function _probing($className)
    {
        Bundle::prob($className);
    }

    private static function _userProbing($className)
    {
        Bundle::resolveUserModule($className, true, true);
    }
}

/*</module>*/
?>