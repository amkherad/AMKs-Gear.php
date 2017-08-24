<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearContext
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearAppContext implements IGearContext
{
    private
        $route,
        $config,
        $request,
        $response,
        //$binderFactory,
        $binder,
        $services,
        $values;

    public function __construct($config)
    {
        //$this->route = $route;
        $this->config = $config;
        //$this->request = $request;
        //$this->response = $response;
        $this->services = [];
        $this->values = [];

        //$this->binder = $binderFactory->createEngine($this);
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    function getConfig()
    {
        return $this->config;
    }

    function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    function getBinder()
    {
        return $this->binder;
    }

    public function setBinder($binder)
    {
        $this->binder = $binder;
    }

    public function setValue($name, $value)
    {
        if (!isset($value) && isset($this->values[$name])) {
            unset($this->values[$name]);
            return;
        }

        $this->values[$name] = $value;
    }

    public function getValue($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        return null;
    }

    public function registerService($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }

    public function removeService($serviceName)
    {
        unset($this->services[$serviceName]);
    }

    public function getService($serviceName)
    {
        return isset($this->services[$serviceName])
            ? $this->services[$serviceName]
            : null;
    }
}

/*</module>*/
?>