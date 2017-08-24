<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\security;
    /*</namespace.current>*/
    /*<namespace.use>*/
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class GearAntiForgeryTokenManager
{
    public static function validateAntiForgeryToken()
    {
        return true;
    }

    public static function generateAntiForgeryToken()
    {
        return '';
    }

    public static function getAntiForgeryToken($createNew = true)
    {

        $antiForgeryToken = self::generateAntiForgeryToken();



        return $antiForgeryToken;
    }
}
/*</module>*/
?>