<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth\services;
    /*</namespace.current>*/
    /*<namespace.use>*/
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IGearAuthCookieService
{
    /**
     * @param $name string
     * @param $value mixed
     * @return mixed
     */
    function setRawCookieVariable($name, $value);

    /**
     * @param $name string
     * @return mixed
     */
    function getRawCookieVariable($name);
}

/*</module>*/
?>