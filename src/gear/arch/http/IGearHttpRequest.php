<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearHttpRequest
{
    /**
     * @param string $name
     * @param mixed|null $defaultValue
     *
     * @return mixed
     */
    function getValue($name, $defaultValue = null);

    /**
     * @return string
     */
    function getBody();

    /**
     * Returns body parameters as array if content is known (e.g. json). otherwise empty array is returned.
     *
     * @return mixed
     */
    function getBodyParameters();

    /**
     * @return array
     */
    function getHeaders();

    /**
     * @param string $name
     * @param string|null $defaultValue
     * @return array|null
     */
    function getHeader($name, $defaultValue = null);

    /**
     * @return string
     */
    function getRawQueryStrings();

    /**
     * @param string $queryString
     * @return mixed
     */
    function setRawQueryStrings($queryString);

    /**
     * @return array
     */
    function getQueryStrings();

    /**
     * @param string $name
     * @return string
     */
    function getQueryString($name);

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    function setQueryString($name, $value);

    /**
     * @return array
     */
    function getFormData();

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    function setFormData($name, $value);

    /**
     * @param string $name
     * @return string
     */
    function getForm($name);

    /**
     * @param string $name
     * @return mixed
     */
    function getFile($name);

    /**
     * @return array
     */
    function getFiles();

    /**
     * @param string $name
     * @param string|null $defaultValue
     * @return string
     */
    function getCookie($name, $defaultValue = null);

    /**
     * @return array
     */
    function getCookies();

    /**
     * @return string
     */
    function getMethod();

    /**
     * @param string $method
     * @return mixed
     */
    function setMethod($method);

    /**
     * @return string
     */
    function getRawUrl();

    /**
     * @return string
     */
    function getContentType();

    /**
     * @param string $contentType
     * @return mixed
     */
    function setContentType($contentType);

    /**
     * @return string
     */
    function getProtocol();

    /**
     * @param string $protocol
     * @return mixed
     */
    function setProtocol($protocol);

    /**
     * @return bool
     */
    function isMultipart();

    /**
     * @return bool
     */
    function isAjaxRequest();

    /**
     * @return bool
     */
    function isJsonRequest();

    /**
     * @return bool
     */
    function isXmlRequest();

    /**
     * @return bool
     */
    function isUrlEncodedRequest();

    /**
     * @return string
     */
    function accept();

    /**
     * @param string $accept
     * @return mixed
     */
    function setAccept($accept);

    /**
     * @return array
     */
    function getAllValues();

    /**
     * @return array
     */
    function getCurrentMethodValues();
}
/*</module>*/
?>