<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearRouteService
{
    function getConfigurator();
    function getMvcContext();
    function createUrl($context, $mvcContext, $params);

    function setUrlProvider($provider);

    function enableCache();
}
/*</module>*/
?>