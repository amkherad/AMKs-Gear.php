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

    public function getBody()
    {
        return file_get_contents("php://input");
    }
    
    public function getHeaders()
    {
        return getallheaders();
    }
    
    public function getQueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }
    
    public function getForm()
    {
        return $_POST;
    }

    function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    function getContentType()
    {
        return $_SERVER['CONTENT_TYPE'];
    }
    
    function getProtocol()
    {
        $protocol = $_SERVER["SERVER_PROTOCOL"];
        $slash = strpos($protocol, '/');
        return substr($protocol, 0, $slash);
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

    /**
     * @return bool
     */
    public function isMultipart()
    {
        return $this->getContentType() == 'multipart';
    }

    /**
     * @return bool
     */
    public function isJsonRequest()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'application/json' ||
            $contentType == 'text/json';
    }

    /**
     * @return bool
     */
    public function isXmlRequest()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'application/xml' ||
            $contentType == 'text/xml'/* || $contentType == 'application/xhtml+xml'*/;
    }

    /**
     * @return bool
     */
    public function isUrlEncodedRequest()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'x-www-form-urlencoded';

    }
}

/*</module>*/
?>