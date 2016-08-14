<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearHttpResponse
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
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
        } elseif (is_object($mixed) || is_array($mixed)) {
            $this->contentType('application/json');
            echo GearSerializer::json($mixed);
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

    public function statusCode($statusCode)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        header(Gear_PoweredResponseHeader, true, $statusCode);
    }

    public function contentType($contentType)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        header("Content-Type: $contentType", true);
    }

    public function setHeader($name, $value)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        header("$name: $value", true);
    }
}

/*</module>*/
?>