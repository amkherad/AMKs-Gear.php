<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\core;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IContext
{
    function GetRoute();
    function GetConfig();
    function GetRequest();
    function GetResponse();
}
/*</module>*/
?>