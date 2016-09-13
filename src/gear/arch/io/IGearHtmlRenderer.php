<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\io;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearHtmlRenderer
{
    /**
     * @param IGearOutputStream $stream
     * @return mixed
     */
    function renderToStream($stream);
}
/*</module>*/
?>