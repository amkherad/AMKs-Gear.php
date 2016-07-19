<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class RouteMvcContext implements IMvcContext
{
    function getAreaName()
    {
        return '';
    }

    function getControllerName()
    {
        return 'home';
    }

    function getActionName()
    {
        return 'index';
    }

    function getParams()
    {
        return '';
    }
}

/*</module>*/
?>