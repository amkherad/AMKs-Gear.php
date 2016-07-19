<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/18/2016
 * Time: 6:46 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IMvcContext
{
    function getAreaName();
    function getControllerName();
    function getActionName();
    function getParams();
}

/*</module>*/
?>