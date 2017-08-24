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
use gear\arch\helpers\GearHelpers;
use gear\arch\http\IGearHttpRequest;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearHttpRequest implements IGearHttpRequest
{
    private $route;
    private $method;
    private $contentType;
    private $queryString;
    private $accept;
    private $requestScheme;
    private $host;
    private $requestUri;

    private $queryStringParameters = [];
    private $formDataParameters = [];
    private $headerParameters = [];
    private $cookieParameters = [];

    private $bodyParameters;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getValue($name, $defaultValue = null)
    {
        if ($this->isJsonRequest()) {
            $params = $this->getBodyParameters();
            if (isset($params[$name])) {
                return $params[$name];
            }
        }

        if (isset($this->queryStringParameters[$name])) {
            return $this->queryStringParameters[$name];
        } elseif (isset($this->formDataParameters[$name])) {
            return $this->formDataParameters[$name];
        } elseif (isset($this->cookieParameters[$name])) {
            return $this->cookieParameters[$name];
        } else {
            if (GearHelpers::tryGetArrayElementByNameCaseInSensetive($_REQUEST, $name, $value)) {
                return $value;
            }
        }
        return $defaultValue;
    }

    public function getBody()
    {
        return file_get_contents("php://input");
    }

    public function getBodyParameters()
    {
        if (isset($this->bodyParameters)) {
            return $this->bodyParameters;
        }

        if ($this->isJsonRequest()) {
            $this->bodyParameters = json_decode($this->getBody());
            return $this->bodyParameters;
        }

        return [];
    }

    public function getHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            $headers = array();
            foreach ($_SERVER as $key => $value) {
                if (preg_match("/^HTTP_X_/", $key))
                    $headers[$key] = $value;
            }
            return $headers;
        }
    }

    public function getHeader($name, $defaultValue = null)
    {
        $name = str_replace('-', '_', $name);
        $name = strtoupper($name);
        if (isset($this->headerParameters[$name])) {
            return $this->headerParameters[$name];
        } else {
            if (isset($_SERVER['HTTP_' . $name])) {
                return $_SERVER['HTTP_' . $name];
            }
        }
        return $defaultValue;
    }

    public function setRawQueryStrings($queryString)
    {
        $this->queryString = $queryString;
    }

    public function getRawQueryStrings()
    {
        if ($this->queryString == null) {
            return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        }
        return $this->queryString;
    }

    public function getQueryStrings()
    {
        return array_merge($_GET, $this->queryStringParameters);
    }

    public function getQueryString($name, $defaultValue = null)
    {
        $name = strtoupper($name);
        if (isset($this->queryStringParameters[$name])) {
            return $this->queryStringParameters[$name];
        } else {
            if (GearHelpers::tryGetArrayElementByNameCaseInSensetive($_GET, $name, $value)) {
                return $value;
            }
        }
        return $defaultValue;
    }

    public function setQueryString($name, $value)
    {
        $this->queryStringParameters[$name] = $value;
    }

    public function getFormData()
    {
        return array_merge($_POST, $this->formDataParameters);
    }

    public function setFormData($name, $value)
    {
        $this->formDataParameters[$name] = $value;
    }

    public function getForm($name, $defaultValue = null)
    {
        if (isset($this->formDataParameters[$name])) {
            return $this->formDataParameters[$name];
        } else {
            if (GearHelpers::tryGetArrayElementByNameCaseInSensetive($_POST, $name, $value)) {
                return $value;
            }
        }
        return $defaultValue;
    }

    /**
     * @param string $name
     * @param string|null $defaultValue
     * @return null
     */
    public function getCookie($name, $defaultValue = null)
    {
        $cookies = $this->getCookies();
        return
            isset($cookies[$name])
                ? $cookies[$name]
                : $defaultValue;
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        $cookieRows = $this->getHeader('Cookie');
        if ($cookieRows == null) {
            return [];
        }

        $cookies = [];

        $cookieList = [];
        foreach ($cookieRows as $cookieStr) {
            $parts = explode(';', $cookieStr);

            if (sizeof($parts) > 0) {
                foreach ($parts as $part) {
                    list($key, $value) = explode('=', $part, 2);
                    $cookies[$key] = $value;
                }
            }
        }

        return $cookieList;
    }

    public function getFile($name)
    {
        return $_FILES[$name];
    }

    public function getFiles()
    {
        return $_FILES;
    }

    public function getMethod()
    {
        if (isset($this->method)) {
            return $this->method;
        }
        $this->method = strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getHost()
    {
        if (isset($this->host)) {
            return $this->host;
        }
        $this->host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getRequestUri()
    {
        if (isset($this->requestUri)) {
            return $this->requestUri;
        }
        $this->requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        return $this->requestUri;
    }

    public function setRequestUri($uri)
    {
        $this->requestUri = $uri;
    }

    public function getRawUrl()
    {
        return $this->getProtocol() . '://' . $this->getHost() . $this->getRequestUri();
    }

    public function getContentType()
    {
        if (isset($this->contentType)) {
            return $this->contentType;
        }
        $this->contentType = $this->getHeader('Content-Type', 'text/html');
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    public function getProtocol()
    {
        if (isset($this->requestScheme)) {
            return $this->requestScheme;
        }
        $this->requestScheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'HTTP';
        return $this->requestScheme;
    }

    public function setProtocol($protocol)
    {
        $this->requestScheme = $protocol;
    }

    public function accept()
    {
        if (isset($this->accept)) {
            return $this->accept;
        }
        $this->accept = $this->getHeader('Accept');
        return $this->accept;
    }

    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    public function getAllValues()
    {
        return array_merge(
            $_REQUEST,
            $this->queryStringParameters,
            $this->formDataParameters,
            $this->cookieParameters,
            $this->getBodyParameters()
        );
    }

    public function getCurrentMethodValues()
    {
        $requestMethod = $this->getMethod();
        switch ($requestMethod) {
            case 'GET':
                return $this->getQueryStrings();
            default:
                if ($this->isJsonRequest()) {
                    return $this->getBodyParameters();
                } else {
                    return $this->getFormData();
                }
        }
    }

    /**
     * @return bool
     */
    public function isMultipart()
    {
        return strtolower(explode('/', $this->getContentType())[0]) == 'multipart';
    }

    /**
     * @return bool
     */
    public function isAjaxRequest()
    {
        return strtolower($this->getHeader('X-Requested-With')) == 'xmlhttprequest';
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
            $contentType == 'text/xml'/* || $contentType == 'application/xhtml+xml'*/
            ;
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