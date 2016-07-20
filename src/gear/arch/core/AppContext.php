<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class AppContext implements IContext
{
    private
        $route,
        $config,
        $request,
        $response,
        $binderFactory,
        $binder,
        $services;

    public function __construct(
        $route,
        $config,
        $request,
        $response,
        $binderFactory)
    {
        $this->route = $route;
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
        $this->binderFactory = $binderFactory;
        $this->services = [];

        $this->binder = $binderFactory->createEngine($this);
    }

    public function getRoute()
    {
        return $this->route;
    }

    function getConfig()
    {
        return $this->config;
    }

    function getRequest()
    {
        return $this->request;
    }

    function getResponse()
    {
        return $this->response;
    }

    function getBinder()
    {
        return $this->binder;
    }

    function registerService($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }
    function removeService($serviceName)
    {
        unset($this->services[$serviceName]);
    }
    function getService($serviceName)
    {
        return isset($this->services[$serviceName])
            ? $this->services[$serviceName]
            : null;
    }
}

/*</module>*/
?>