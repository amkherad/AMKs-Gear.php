<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IOutputStream
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\io;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\io\IOutputStream;
use gear\arch\core\Serializer;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class InMemoryStream implements IOutputStream
{
    private
        $buffer;

    public function write($mixed)
    {
        if (is_string($mixed)) {
            $this->buffer = $this->buffer . $mixed;
        } else {
            $this->buffer = $this->buffer . Serializer::stringify($mixed);
        }
    }

    public function clear(){
        $this->buffer = '';
    }

    public function &getBuffer(){
        return $this->buffer;
    }
}
/*</module>*/
?>