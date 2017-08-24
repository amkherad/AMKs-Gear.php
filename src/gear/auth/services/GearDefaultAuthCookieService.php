<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearAuthCookieService
/*</requires>*/

/*<namespace.current>*/
namespace gear\auth\services;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\services\IGearAuthCookieService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultAuthCookieService implements IGearAuthCookieService
{
    public function setRawCookieVariable($name, $value)
    {
        $_COOKIE[$name] = $value;
    }

    public function getRawCookieVariable($name)
    {
        return isset($_COOKIE[$name])
            ? $_COOKIE[$name]
            : null;
    }
}
/*</module>*/
?>