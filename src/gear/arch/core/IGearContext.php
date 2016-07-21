<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearContext
{
    function getRoute();
    function getConfig();
    function getRequest();
    function getResponse();
    function getBinder();

    function registerService($serviceName, $service);
    function removeService($serviceName);
    function getService($serviceName);
}
/*</module>*/
?>