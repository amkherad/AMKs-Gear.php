<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace GearCms\helpers;
    /*</namespace.current>*/
    /*<namespace.use>*/
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class gcAuthentication
{
    private static
        $authenticationManager;

    public static function overrideAuthenticationManager($authenticationManager)
    {
        self::$authenticationManager = $authenticationManager;
    }

    public static function checkUserAuthorization($context, $user, $role = null)
    {

    }
}

/*</module>*/
?>