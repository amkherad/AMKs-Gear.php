<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 3:25 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IHttpResponse;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class HttpResponse implements IHttpResponse
{
    public function write($mixed)
    {
        echo Serializer::stringify($mixed);
    }
    public function serializeWrite($object, $request)
    {

    }
}

/*</module>*/
?>