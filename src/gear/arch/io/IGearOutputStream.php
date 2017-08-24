<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\io;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearOutputStream
{
    function write($mixed);
    function clear();
    function &getBuffer();
    function bufferSize();
}
/*</module>*/
?>