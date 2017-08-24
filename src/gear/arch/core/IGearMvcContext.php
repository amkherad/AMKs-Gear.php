<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearMvcContext
{
    /**
     * @return string
     */
    function getAreaName();
    /**
     * @return string
     */
    function getControllerName();
    /**
     * @return string
     */
    function getActionName();
    /**
     * @return array
     */
    function getParams();
}

/*</module>*/
?>