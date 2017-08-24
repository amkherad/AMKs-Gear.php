<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearOutputStream
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\io\IGearOutputStream;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearHttpResponse extends IGearOutputStream
{
    //function write($mixed);
    /**
     * @param mixed $object
     * @param IGearHttpRequest $request
     * @return mixed
     */
    function serializeWrite($object, $request);

    /**
     * @param mixed $mixed
     * @return mixed
     */
    function writeInnerStream($mixed);

    /**
     * @return mixed
     */
    function flushInnerStream();

    /**
     * @return IGearOutputStream
     */
    function getInnerStream();

    /**
     * @return IGearOutputStream
     */
    function getBufferStream();

    /**
     * @return int
     */
    function getStatusCode();

    /**
     * @param int $statusCode
     * @return mixed
     */
    function setStatusCode($statusCode);

    /**
     * @return string
     */
    function getContentType();

    /**
     * @param string $contentType
     * @return mixed|void
     */
    function setContentType($contentType);

    /**
     * @param string $name
     * @param string|null $defaultValue
     * @return string
     */
    function getHeader($name, $defaultValue = null);

    /**
     * @param string $name
     * @param string $value
     * @param bool $replace
     * @return mixed
     */
    function setHeader($name, $value, $replace = true);

    /**
     * @param string $header
     * @param bool $replace
     * @return mixed
     */
    function setHeaderLegacy($header, $replace = true);

    /**
     * @param string $name
     * @param string $value
     * @return bool
     */
    function headerExists($name, &$value);

    /**
     * @return array
     */
    function getHeaders();

    /**
     * @return string
     */
    function getEncoding();

    /**
     * @param string $encoding
     * @return mixed
     */
    function setEncoding($encoding);

    /**
     * @param string $name
     * @param string $value
     * @param bool|null $expire
     * @return mixed
     */
    function setCookie($name, $value, $expire = null);

    /**
     * @return bool
     */
    function isRedirect();

    /**
     * @return string
     */
    function redirectUrl();

    /**
     * @return bool
     */
    function isJson();

    /**
     * @return bool
     */
    function isXml();

    /**
     * @return bool
     */
    function isPlainText();

    /**
     * @return bool
     */
    function isHtml();

    /**
     * @param string $name
     * @return array
     */
    function getCookie($name);

    /**
     * @return array
     */
    function getCookies();

    /**
     * @param bool $status
     * @return mixed
     */
    function setBufferStatus($status);

    /**
     * @return void
     */
    function flush();

    function getBody();
}
/*</module>*/
?>