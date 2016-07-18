<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\core;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class AppContext implements IContext
{
    private
        $route,
        $config,
        $request,
        $response;

    public function __construct(
        $route,
        $config,
        $request,
        $response)
    {
        $this->route = $route;
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
    }

    public function GetRoute()
    {
        return $this->route;
    }

    function GetConfig()
    {
        return $this->config;
    }

    function GetRequest()
    {
        return $this->request;
    }

    function GetResponse()
    {
        return $this->response;
    }
}
/*</module>*/
?>