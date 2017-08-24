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
interface IGearAuthSessionService
{
    /**
     * @param $name string
     * @param $value mixed
     * @return mixed
     */
    function setRawSessionVariable($name, $value);

    /**
     * @param $name string
     * @return mixed
     */
    function getRawSessionVariable($name);
}
/*</module>*/
?>