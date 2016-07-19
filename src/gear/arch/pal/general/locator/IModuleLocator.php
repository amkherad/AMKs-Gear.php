<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\pal\locator;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IModuleLocator
{
    function Exists($module, $descriptor, $context);
    function GetAbsolutePath($module, $descriptor, $context);
    function Add($module, $descriptor, $context);
}
/*</module>*/
?>