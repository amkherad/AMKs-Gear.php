<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IOutputStream
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\io\IOutputStream;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IHttpResponse extends IOutputStream
{
    function write($mixed);
    function serializeWrite($object, $request);

    function writeInnerStream();
}
/*</module>*/
?>