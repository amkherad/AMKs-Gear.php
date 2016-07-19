<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IContext
{
    function getRoute();
    function getConfig();
    function getRequest();
    function getResponse();
    function getBinder();
}
/*</module>*/
?>