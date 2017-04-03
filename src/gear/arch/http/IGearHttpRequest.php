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
     * @return array
     */
    function getHeaders();
    
    /**
     * @return string
     */
    function getHeader($name);

    /**
     * @return array
     */
    function getQueryStrings();

    /**
     * @return array
     */
    function getForms();
    
    /**
     * @return string
     */
    function getQueryString($name);
    
    /**
     * @return string
     */
    function getForm($name);

    /**
     * @return string
     */
    function getMethod();

    /**
     * @return string
     */
    function getRawUrl();

    /**
     * @return string
     */
    function getContentType();

    /**
     * @return string
     */
    function getProtocol();

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
    function accepts();

    /**
     * @return array
     */
    function getAllValues();

    /**
     * @return array
     */
    function &getCurrentMethodValues();
}
/*</module>*/
?>