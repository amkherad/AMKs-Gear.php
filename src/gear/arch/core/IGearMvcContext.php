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
    function getAreaName();
    function getControllerName();
    function getActionName();
    function getParams();
}

/*</module>*/
?>