<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearAuthSessionService
/*</requires>*/

/*<namespace.current>*/
namespace gear\auth\services;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\services\IGearAuthSessionService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultAuthSessionService implements IGearAuthSessionService
{
    public function setRawSessionVariable($name, $value)
    {
        session_start();
        $_SESSION[$name] = $value;
    }

    public function getRawSessionVariable($name)
    {
        return isset($_SESSION[$name])
            ? $_SESSION[$name]
            : null;
    }
}
/*</module>*/
?>