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
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            $headers = array();
            foreach($_SERVER as $key => $value)
            {
                if(preg_match("/^HTTP_X_/", $key))
                    $headers[$key] = $value;
            }
            return $headers;
        }
    }
    
    public function getHeader($name, $defaultValue = null)
    {
        $name = str_replace('-', '_', $name);
        $name = strtoupper($name);
        return isset($_SERVER['HTTP_' . $name])
            ? $_SERVER['HTTP_' . $name]
            : $defaultValue;
    }
    
    public function getQueryStrings()
    {
        return $_SERVER['QUERY_STRING'];
    }
    
    public function getQueryString($name, $defaultValue = null)
    {
        return isset($_GET[$name])
            ? $_GET[$name]
            : $defaultValue;
    }
    
    public function getForms()
    {
        return $_POST;
    }
    
    public function getForm($name, $defaultValue = null)
    {
        return isset($_POST[$name])
            ? $_POST[$name]
            : $defaultValue;
    }

    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function getRawUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function getContentType()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) {
            return $_SERVER['CONTENT_TYPE'];
        }
        return 'text/html';
    }

    public function getProtocol()
    {
        return $_SERVER['REQUEST_SCHEME'];
        //$protocol = $_SERVER["SERVER_PROTOCOL"];
        //$slash = strpos($protocol, '/');
        //return substr($protocol, 0, $slash);
    }

    public function accepts()
    {

    }

    public function getAllValues()
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
    public function isAjaxRequest()
    {
        return $this->getHeader('X-Requested-With') == 'XMLHttpRequest';
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