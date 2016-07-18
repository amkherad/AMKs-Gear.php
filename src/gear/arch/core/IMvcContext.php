<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/18/2016
 * Time: 6:46 AM
 */
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\core;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IMvcContext
{
    function ControllerName();
    function ActionName();
    function GetParams();
}

/*</module>*/
?>