<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\pal\locator;
/*</namespaces>*/

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