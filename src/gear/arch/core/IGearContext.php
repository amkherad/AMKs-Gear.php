<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearConfiguration;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
use gear\arch\model\IGearModelBinder;
use gear\arch\route\IGearRouteService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearContext
{
    /** @return IGearRouteService */
    function getRoute();
    /** @return GearConfiguration */
    function getConfig();
    /** @return IGearHttpRequest */
    function getRequest();
    /** @return IGearHttpResponse */
    function getResponse();
    /** @return IGearModelBinder */
    function getBinder();

    /**
     * Registers a public service into context.
     *
     * @param $serviceName string
     * @param $service mixed
     * @return void
     */
    function registerService($serviceName, $service);

    /**
     * Removes a service from context.
     *
     * @param $serviceName
     * @return mixed
     */
    function removeService($serviceName);

    /**
     * Retrieves a public service from context.
     *
     * @param $serviceName string
     * @return mixed Requested service.
     */
    function getService($serviceName);
}
/*</module>*/
?>