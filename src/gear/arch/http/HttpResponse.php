<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IHttpResponse
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IHttpResponse;
use gear\arch\core\Serializer;
use gear\arch\io\HtmlStream;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class HttpResponse implements IHttpResponse
{
    private
        $innerStream;

    public function __construct()
    {
        $this->innerStream = new HtmlStream();
    }

    public function getInnerStream()
    {
        return $this->innerStream;
    }

    public function write($mixed)
    {
        if (is_string($mixed)) {
            echo $mixed;
        } else {
            echo Serializer::stringify($mixed);
        }
    }
    public function clear()
    {

    }

    public function serializeWrite($object, $request)
    {
        echo Serializer::stringify($object);
    }

    public function writeInnerStream()
    {
        $this->write($this->innerStream->getBuffer());
    }
}
/*</module>*/
?>