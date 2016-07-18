<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\contoller;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IControllerFactory
{
    function CreateController($controllerName, $actionName, $context);
    function Exists($controllerName, $actionName, $context);
}
/*</module>*/
?>