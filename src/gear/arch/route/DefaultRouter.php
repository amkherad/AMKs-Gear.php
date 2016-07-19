<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 1:30 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class DefaultRouter implements IRouteService
{
    function getMvcContext()
    {
        return new RouteMvcContext();
    }
}
/*</module>*/
?>