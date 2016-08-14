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
    function write($mixed);
    function serializeWrite($object, $request);

    function writeInnerStream();

    function statusCode($statusCode);
    function contentType($contentType);
    function setHeader($name, $value);
}
/*</module>*/
?>