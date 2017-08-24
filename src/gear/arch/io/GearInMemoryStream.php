<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearOutputStream
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\io;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\io\IGearOutputStream;
use gear\arch\core\GearSerializer;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearInMemoryStream implements IGearOutputStream
{
    private $buffer;

    public function write($mixed)
    {
        if (is_string($mixed)) {
            $this->buffer .= $mixed;
        } else {
            $this->buffer .= GearSerializer::stringify($mixed);
        }
    }

    public function clear()
    {
        $this->buffer = null;
    }

    public function &getBuffer()
    {
        return $this->buffer;
    }

    public function bufferSize()
    {
        return strlen($this->buffer);
    }

    public function getNewBuffer()
    {
        return $this->buffer;
    }
}

/*</module>*/
?>