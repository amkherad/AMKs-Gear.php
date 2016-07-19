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
        $binder;

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
}
/*</module>*/
?>