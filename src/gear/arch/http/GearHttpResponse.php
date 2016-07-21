<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearHttpResponse
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IGearHttpResponse;
use gear\arch\core\GearSerializer;
use gear\arch\io\GearHtmlStream;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearHttpResponse implements IGearHttpResponse
{
    private
        $innerStream;

    public function __construct()
    {
        $this->innerStream = new GearHtmlStream();
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
            echo GearSerializer::stringify($mixed);
        }
    }
    public function clear()
    {

    }

    public function serializeWrite($object, $request)
    {
        echo GearSerializer::stringify($object);
    }

    public function writeInnerStream()
    {
        $this->write($this->innerStream->getBuffer());
    }
}
/*</module>*/
?>