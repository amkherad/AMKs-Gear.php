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
/*<namespace.use>*/
use gear\arch\http\IGearHttpRequest;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHttpRequest implements IGearHttpRequest
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getValue($name, $defaultValue = null)
    {
        if (!isset($_REQUEST[$name])) {
            return $defaultValue;
        }
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
        $requestMethod = $this->getMethod();// $_SERVER['REQUEST_METHOD'];
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