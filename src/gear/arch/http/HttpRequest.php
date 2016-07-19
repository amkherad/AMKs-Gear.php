<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 1:39 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class HttpRequest implements IHttpRequest
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getValue($name)
    {
        return $_REQUEST[$name];
    }

    function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    function accepts()
    {

    }

    function getAllValues()
    {
        return $_REQUEST;
    }

    public function &getCurrentMethodValues()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        switch ($requestMethod) {
            case'GET':
                return $_GET;
            //case'POST':return$this->POST;
            default:
                return $_POST;
        }
    }
}

/*</module>*/
?>